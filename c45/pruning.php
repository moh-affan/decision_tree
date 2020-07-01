<?php
// function pprint($data)
// {
//   print("<pre>" . print_r($data, true) . "</pre>");
// }
// $str_tree = file_get_contents('tree.json');
// $m_tree = (array) json_decode($str_tree, TRUE, 512, JSON_OBJECT_AS_ARRAY);
// define("T", "T");
// define("B", "B");

function contains_leaf($node)
{
  foreach ($node as $n) {
    if (is_array($n))
      return FALSE;
  }
  return TRUE;
}

function pruning($tree)
{
  $new_tree = [];
  foreach ($tree as $k => $t) {
    if (is_array($t)) {
      if (contains_leaf($t)) {
        $c_vals = array_count_values($t);
        if (count($c_vals) == 1)
          $new_tree[$k] = $t;
        else {
          $max = max(array_values($c_vals));
          // pprint($max);
          if ($max > 1) {
            $max_id = array_search($max, $c_vals);
            $new_tree[$k] = $max_id;
          } else {
            $new_tree[$k] = $t;
          }
        }
      } else {
        $new_tree[$k] = pruning($t);
      }
    } else {
      $new_tree[$k] = $t;
    }
  }
  return $new_tree;
}

// $new = pruning($m_tree);
// file_put_contents('pruning.json', json_encode($new));
