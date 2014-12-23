<?php

namespace Personado;
use Theme;
use Graph;
use Plot;
use GroupBarPlot;
use AccBarPlot;
use BarPlot;
use LinePlot;
use PiePlot;
use PiePlot3D;

/**
* Universal Theme class
*/
class PersonadoTheme extends Theme
{
    private $font_color       = '#444444';
    private $background_color = '#ffffff';
    private $axis_color       = '#888888';
    private $grid_color       = '#E3E3E3';

    function GetColorList() {
        return array(
            '#5f8b95',#blue
            '#f381b9',#red
            '#61E3A9',#green

            #'#D56DE2',
            '#85eD82',
            '#F7b7b7',
            '#CFDF49',
            '#88d8f2',
            '#07AF7B',
            '#B9E3F9',
            '#FFF3AD',
            '#EF606A',
            '#EC8833',
            '#FFF100',
            '#87C9A5',
        );
    }

    function SetupGraph(Graph $graph) {

        // graph
        /*
        $img = $graph->img;
        $height = $img->height;
        $graph->SetMargin($img->left_margin, $img->right_margin, $img->top_margin, $height * 0.25);
        */
        $graph->SetFrame(false);
        $graph->SetMarginColor('#ffffff');
        $graph->SetBox(true, '#DADADA');
//        $graph->SetBackgroundGradient($this->background_color, '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

        // legend
        $graph->legend->SetFrameWeight(0);
        $graph->legend->Pos(0.5, 0.85, 'center', 'top');
        $graph->legend->SetFillColor('#ffffff');
        $graph->legend->SetLayout(LEGEND_HOR);
        $graph->legend->SetColumns(3);
        $graph->legend->SetShadow(false);
        $graph->legend->SetMarkAbsSize(5);

        // xaxis
        $graph->xaxis->title->SetFont(FF_OPENSANS);
        $graph->xaxis->title->SetColor($this->font_color);
        $graph->xaxis->SetColor($this->axis_color, $this->font_color);
        $graph->xaxis->SetTickSide(SIDE_BOTTOM);
        $graph->xaxis->HideTicks();
        $graph->xaxis->SetTitleMargin(15);
                
        // yaxis
        $graph->yaxis->title->SetFont(FF_OPENSANS);
        $graph->yaxis->title->SetColor($this->font_color);
        $graph->yaxis->SetColor($this->axis_color, $this->font_color);
        $graph->yaxis->SetTickSide(SIDE_LEFT);
        $graph->yaxis->SetLabelMargin(8);
        $graph->yaxis->SetTickPositions(array(50, 100, 150));
//        $graph->yaxis->HideLine();
        $graph->yaxis->HideTicks();

        // grid
        $graph->ygrid->SetColor($this->grid_color);
        $graph->ygrid->SetFill(false, '#ffffff', '#ffffff');
 //       $graph->ygrid->SetLineStyle('dotted');


        // font
        $graph->title->SetFont(FF_OPENSANS);
        $graph->title->SetColor($this->font_color);
        $graph->subtitle->SetColor($this->font_color);
        $graph->subtitle->SetFont(FF_OPENSANS);
        $graph->subsubtitle->SetColor($this->font_color);
        $graph->subsubtitle->SetFont(FF_OPENSANS);

        $graph->img->SetAntiAliasing();
    }


    function SetupPieGraph(Graph $graph) {

        // graph
        $graph->SetFrame(false);

        // legend
        $graph->legend->SetFillColor('#ffffff');

        $graph->legend->SetFrameWeight(0);
        $graph->legend->Pos(0.5, 0.80, 'center', 'top');
        $graph->legend->SetLayout(LEGEND_HOR);
        $graph->legend->SetColumns(4);

        $graph->legend->SetShadow(false);
        $graph->legend->SetMarkAbsSize(5);

        // title
        $graph->title->SetFont(FF_OPENSANS);
        $graph->title->SetColor($this->font_color);
        $graph->subtitle->SetColor($this->font_color);
        $graph->subtitle->SetFont(FF_OPENSANS);
        $graph->subsubtitle->SetColor($this->font_color);
        $graph->subsubtitle->SetFont(FF_OPENSANS);
    }


    function PreStrokeApply(Graph $graph) {
        if ($graph->legend->HasItems()) {
            $img = $graph->img;
            $height = $img->height;
            $graph->SetMargin(
                $img->raw_left_margin, 
                $img->raw_right_margin, 
                $img->raw_top_margin, 
                $height * 0.25
            );
        }
    }

    /**
     * @param Plot $plot
     */
    function ApplyPlot($plot) {

        switch (true)
        { 
            case $plot instanceof GroupBarPlot:
            {
                foreach ($plot->plots as $_plot) {
                    $this->ApplyPlot($_plot);
                }
                break;
            }

            case $plot instanceof AccBarPlot:
            {
                foreach ($plot->plots as $_plot) {
                    $this->ApplyPlot($_plot);
                }
                break;
            }

            case $plot instanceof BarPlot:
            {
                $plot->Clear();

                $color = $this->GetNextColor();
                $plot->SetColor($color);
                $plot->SetFillColor($color);
                break;
            }

            case $plot instanceof LinePlot:
            {
                $plot->Clear();
                $plot->SetColor($this->GetNextColor().'@0.4');
                $plot->SetWeight(2);
                break;
            }

            case $plot instanceof PiePlot:
            {
                $plot->SetCenter(0.5, 0.45);
                $plot->ShowBorder(false);
                $plot->SetSliceColors($this->GetThemeColors());
                break;
            }

            case $plot instanceof PiePlot3D:
            {
                $plot->value->SetFont(FF_OPENSANS);
                $plot->SetSliceColors($this->GetThemeColors());
                break;
            }
    
            default:
            {
            }
        }
    }
}


?>
