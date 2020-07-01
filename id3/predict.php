<?php
function pprint($data)
{
  print("<pre>" . print_r($data, true) . "</pre>");
}
function tree_print($TreeArray, $deep = 0)
{
  $padding = str_repeat('-', $deep * 1);
  echo "<div style='font-size:10px'>";
  echo $padding . "<ul>\n";
  foreach ($TreeArray as $k => $arr) {
    echo $k . "  <li>\n";
    if (is_array($arr)) {
      tree_print($arr, $deep + 1);
    } else {
      echo $padding . ' ' . $arr;
    }
    echo $padding . "  </li>\n";
  }
  echo $padding . "</ul>\n";
  echo "</div>";
}

$str_tree = file_get_contents('tree.json');
$tree = (array) json_decode($str_tree, TRUE, 512, JSON_OBJECT_AS_ARRAY);
define("T", "T");
define("B", "B");

function predict($query, $tree, $default = 1)
{
  $query_keys = array_keys($query);
  $tree_keys = array_keys($tree);
  $result = null;
  foreach ($query_keys as $q) {
    // pprint($q);
    if (in_array($q, $tree_keys)) {
      if (isset($tree[$q][$query[$q]]))
        $result = $tree[$q][$query[$q]];
      else
        return $default;

      $result = $tree[$q][$query[$q]];
      if (is_array($result))
        return predict($query, $result);
      else
        return $result;
    }
  }
}

$test = [
  'G1' => 'B',
  'G2' => 'T',
  'G3' => 'T',
  'G4' => 'B',
  'G5' => 'T',
  'G6' => 'T',
  'G7' => 'T',
  'G8' => 'B',
  'G9' => 'T',
  'G10' => 'T',
  'G11' => 'T',
  'G12' => 'T',
  'G13' => 'T',
  'G14' => 'T',
  'G15' => 'B',
  'G16' => 'T',
  'G17' => 'T',
  'G18' => 'T',
  'G19' => 'B',
  'G20' => 'T',
  'G21' => 'B',
];
$t2 = array_combine(array_keys($test), [B, T, B, T, B, T, T, T, T, T, T, T, T, T, T, T, T, T, T, B, T]); // harusnya bernilai D3
$t3 = array_combine(array_keys($test), [T, B, T, T, T, T, T, B, T, T, T, T, T, B, T, T, T, T, T, T, T]); // harusnya bernilai D1
$t4 = array_combine(array_keys($test), [T, T, T, B, T, T, T, T, T, T, B, T, T, T, T, T, B, T, T, T, T]); // harusnya bernilai D4
// $predictions = predict($test, $tree);
$predictions = predict($t4, $tree);
// pprint($predictions);
tree_print($tree);
pprint($tree);
