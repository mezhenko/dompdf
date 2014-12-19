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

        $labels = array_map(function($cell){
            return $cell->label;
        }, $options->dataSource);

        $formatValue = function ($aLabel) {
            return is_numeric($aLabel)
            ? ($aLabel * 100) . '%'
            : $aLabel;
        };

        $maxval = max($ydata);

        \FB::log($labels);
        \FB::log($ydata);


        // Create a graph instance
        $graph = new Graph($width,$height);
        $graph->SetScale('textlin');
        $graph->SetTheme(new PersonadoTheme());
        $graph->SetMargin(100,1,25,100);

        // Setup a title for the graph
        $graph->title->Hide(true);

        // Create the linear plot
        $lineplot=new BarPlot($ydata);
        $graph->Add($lineplot);

        $lineplot->SetValuePos('top');
        $lineplot->value->HideZero(false);
        $lineplot->value->SetAlign('center','top');
        $lineplot->value->SetFormatCallback($formatValue);
        $lineplot->value->SetColor('#5f8b95');
        $lineplot->value->Show(true);

        // Add the plot to the graph
        $graph->xaxis->SetTickLabels($labels);
        $graph->yaxis->SetLabelFormatCallback($formatValue);
        $graph->yaxis->SetTickPositions(range(0,$maxval*2,0.05));
        $graph->yaxis->scale->SetGrace(5);
//        $graph->yaxis->HideLabels();

        $graph->SetImgFormat('png');
        return $graph->Stroke(_IMG_HANDLER);
    }
}