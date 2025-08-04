<?php

$query= $args["query"];
$pager= $args["pager"];
$page = $pager['current_page'];
$total = $pager['total_pages'];
$prev = $page - 1;
$next = $page + 1;
$arr = array();
$offset = 3;
$break = 4;


if ($total > 1) {

    if ($total > $break + $offset + 1) {

        if ($page <= $break) {
            for ($i = 1; $i <= $offset + $break; $i++) {
                if ($i <= $page + $offset && $i <= $total) {
                    array_push($arr, $i);
                }
            }
        } else {
            array_push($arr, 1);
            array_push($arr, null);
        }

        if ($page > $break && $page <= $total - $break) {
            for ($i = $offset; $i < $total; $i++) {
                if ($i <= $page + $offset  && $i >= $page - $offset) {
                    array_push($arr, $i);
                }
            }
        }
        if ($page > $total - $break) {
            for ($i = $total - $break - $offset; $i <= $total; $i++) {
                if ($i >= $page - $offset) {
                    array_push($arr, $i);
                }
            }
        } else {
            array_push($arr, null);
            array_push($arr, $total);
        }
    } else {
        for ($i = 1; $i <= $total; $i++) {
            array_push($arr, $i);
        }
    }

    //
    echo '<div class="pagination">';
    echo $prev >= 1 ? '<a rel="prev" href="' . $query . $prev . '" class="btn-1 prev">' . component::icon("arrow-left", 9, 14) . '</a>' : '<button class="btn-1 prev disabled">' . component::icon("arrow-left", 9, 14) . '</button>';
    for ($i = 0; $i < count($arr); $i++) {
        $index = $arr[$i];
        $active = ($index == $page) ? ' class="active"' : '';
        echo ($index === null) ?  "<span>...</span>" :  '<a href="' . $query . $index . '"' . $active . '>' . $index . '</a>';
    }
    echo $next <= $total ? '<a rel="next" href="' . $query  . $next . '" class="btn-1 next">' . component::icon("arrow-right", 9, 14) . '</a>' : '<button class="btn-1 next disabled">' . component::icon("arrow-right", 9, 14) . '</button>';
    echo '</div>';
}
