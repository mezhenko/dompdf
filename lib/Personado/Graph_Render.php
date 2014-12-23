<?php
/**
 * Created by IntelliJ IDEA.
 * User: hitori
 * Date: 13.12.14
 * Time: 19:15
 */

namespace Personado;

use BarPlot;
use Graph;


function break_lines(array $labels){
    return array_map(function($label){
        $label = trim(str_replace("\n"," ",$label));
        $labelLength = mb_strlen($label);
        if($labelLength > 30){
            $mid = round($labelLength / 2);
            $rightPart = substr($label,$mid);

            $break = strpos($rightPart,' ');
            if($break === false){
                $leftPart = substr($label,0,$mid);
                $break = strrpos($leftPart,' ');
            }
            if($break === false){
                $break = $mid;
            }

            $ret = substr($label,0,$mid+$break)."\n".substr($label,$mid+1+$break);
            if(mb_strlen($ret)>50){
                $ret = substr($ret,0,47)."...";
            }
            return $ret;
        }else{
            return $label;
        }
    },$labels);
}

/**
 * @param $label
 * @param $fontSize
 * @return array
 */
function get_bbox($label, $fontSize){
    $textbox = imagettfbbox($fontSize, 0, DOMPDF_FONT_DIR . 'OpenSans-Regular.ttf', $label);
    return [
        $textbox[2],
        abs($textbox[5]-$textbox[3])
    ];
}

function prepare_labels($labels, $graphAreaX,$fontSize = 20){
    $mapBbox = function($fontSize){
        return function ($label) use ($fontSize) {
            return get_bbox($label, $fontSize);
        };
    };

    $brokenLabels = break_lines($labels);

    $bboxes = array_map($mapBbox($fontSize),$brokenLabels);

    $maxLabelLength  = $graphAreaX/count($labels) - 20;

    if(\Functional\first($bboxes,function($bbox)use($maxLabelLength){
        return $bbox[0] > $maxLabelLength;
    })){
        $textAngle = 0.45;
    }else{
        $textAngle  = 0;
    }

    $leftMargin = \Functional\reduce_left($bboxes,function($bbox,$position,$__,$margin)use($maxLabelLength,$textAngle){
        return max($margin,$bbox[0]*cos($textAngle)-($position+0.5)*$maxLabelLength);
    },0);

    $bottomMargin = \Functional\reduce_left($bboxes,function($bbox,$position,$__,$margin)use($maxLabelLength,$textAngle){
        return max($margin,$bbox[0]*sin($textAngle)+$bbox[1]*cos($textAngle));
    },0);

    $maxLabelLength  = ($graphAreaX-$leftMargin)/count($labels) - 20;
    if(\Functional\first($bboxes,function($bbox)use($maxLabelLength){
        return $bbox[1] > $maxLabelLength;
    })) {
        return prepare_labels($labels,$graphAreaX, $fontSize * (0.6));
    }else{
        return [$brokenLabels, $fontSize, $textAngle, $leftMargin, $bottomMargin];
    }

}

class Graph_Render {
    /**
     * @param $options
     * @return resource
        dataSource: dataItems
        title:
          if @props.label
            text: @props.label
        width: width
        height: height
     *
        series:
          argumentField : "label"
          valueField    : "value"
          label:
            visible: rue
            format: 'percent'
            connector:
              visible: true
        tooltip:
          enabled          : true
          percentPrecision : 2
          customizeTooltip: (value) ->
            text: value.percentText
        legend:
          horizontalAlignment : "center"
          verticalAlignment   : "bottom"
     */

    public static function drawBar(Graph_Options $options){
        // Width and height of the graph
        $width = $options->width;
        $height = $options->height;

        $ydata = array_map(function($cell){
            return $cell->value;
        }, $options->dataSource);

        $labels = break_lines(array_map(function($cell){
            return $cell->label;
        }, $options->dataSource));

        $formatValue = function ($aLabel) {
            return is_numeric($aLabel)
            ? number_format(($aLabel * 100),0) . '%'
            : $aLabel;
        };

        $formatFlValue = function ($aLabel) {
            return is_numeric($aLabel)
            ? number_format(($aLabel * 100),2) . '%'
            : $aLabel;
        };

        $maxval = max($ydata);

        list($newLabels, $fontSize, $textAngle, $leftMargin, $bottomMargin) =
            prepare_labels($labels, $width - 100);


        \FB::log($newLabels);
        \FB::log($ydata);

        // Create a graph instance
        $graph = new Graph($width,$height);
        $graph->SetScale('textlin');
        $graph->SetTheme(new PersonadoTheme());
        $graph->SetMargin($leftMargin + 100,1,25, $bottomMargin + 20);

        $graph->title->SetFont(FF_OPENSANS,FS_NORMAL,$fontSize);
        $graph->subtitle->SetFont(FF_OPENSANS);
        $graph->subsubtitle->SetFont(FF_OPENSANS);
        $graph->yaxis->SetFont(FF_OPENSANS,FS_NORMAL,20);
        $graph->yaxis->SetLabelMargin(20);
        $graph->xaxis->SetFont(FF_OPENSANS,FS_NORMAL,$fontSize);
        $graph->xaxis->SetLabelMargin(10);
        $graph->xaxis->SetLabelAngle($textAngle);


        // Setup a title for the graph
        $graph->title->Hide(true);

        // Create the linear plot
        $lineplot=new BarPlot($ydata);
        $graph->Add($lineplot);

        $lineplot->SetValuePos('top');
        $lineplot->value->HideZero(false);
        $lineplot->value->SetFormatCallback($formatFlValue);
        $lineplot->value->SetColor('#5f8b95');
        $lineplot->value->Show(true);
        $lineplot->value->SetFont(FF_OPENSANS, FS_BOLD,$fontSize*(0.6));

        if($textAngle){
            $lineplot->value->SetAlign('right','top');
        }else{
            $lineplot->value->SetAlign('center','top');
        }

        // Add the plot to the graph
        $graph->xaxis->SetTickLabels($labels);
        $graph->xaxis->SetLabelAngle($textAngle/M_PI*180);

        $graph->yaxis->SetLabelFormatCallback($formatValue);

        $upperVal = $maxval + 0.05;
        $graph->yaxis->SetTickPositions(range(0,$upperVal, $upperVal / 10));
        $graph->yaxis->scale->SetGrace(5);

        $graph->SetImgFormat('png');
        return $graph->Stroke(_IMG_HANDLER);
    }
}