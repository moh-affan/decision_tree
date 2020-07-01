<?php
ini_set('max_execution_time', '300');
function pprint($data)
{
  print("<pre>" . print_r($data, true) . "</pre>");
}

$csv = file('dataset100.csv');
$data = [];
foreach ($csv as $line) {
  $data[] = str_getcsv($line, ';');
}
$header = $data[0];
$g_features = $header;
array_pop($g_features);
$dataset = array_slice($data, 1);
$target = array_column($dataset, (count($header) - 1));
for ($i = 0; $i < count($dataset); $i++) {
  $dataset[$i] = array_combine($header, $dataset[$i]);
}

function entropy($target_col)
{
  $values = array_count_values($target_col);
  $elements = array_keys($values);
  $counts = array_values($values);
  $sum = array_sum($counts);
  $entropy = 0;
  for ($i = 0; $i < count($elements); $i++) {
    $entropy += ((-$counts[$i] / $sum) * log($counts[$i] / $sum, 2));
  }
  return $entropy;
}

function info_gain($data, $split_attribute_name, $target_name = 'hasil')
{
  $col_data = array_column($data, $target_name);
  $total_entropy = entropy($col_data);
  $split_col_data = array_column($data, $split_attribute_name);
  $count_vals = array_count_values($split_col_data);
  $vals = array_keys($count_vals);
  $counts = array_values($count_vals);
  $sum = array_sum($counts);
  $weighted_entropy = 0;
  for ($i = 0; $i < count($vals); $i++) {
    $new_data = array_filter($data, function ($value) use ($split_attribute_name, $vals, $i) {
      return $value[$split_attribute_name] == $vals[$i];
    });
    $col_data = array_column($new_data, $target_name);
    $weighted_entropy += (($counts[$i] / $sum) * entropy($col_data));
  }
  $information_gain = $total_entropy - $weighted_entropy;
  return $information_gain;
}

function split_info($data, $split_attribute_name)
{
  $split_col_data = array_column($data, $split_attribute_name);
  $count_vals = array_count_values($split_col_data);
  $vals = array_keys($count_vals);
  $counts = array_values($count_vals);
  $sum = array_sum($counts);
  // pprint($counts);
  // pprint($sum);
  $si = 0;
  for ($i = 0; $i < count($vals); $i++) {
    $si += ((-$counts[$i] / $sum) * log((($counts[$i] / $sum)), 2));
  }
  // pprint($si);
  return $si;
}

function c45($data, $origin, $features, $target_attribute_name = 'hasil', $parent_node_class = NULL)
{
  $target = array_column($data, $target_attribute_name);
  $uniq = array_unique($target);
  if (count($uniq) <= 1) {
    return $uniq[0];
  } elseif (count($data) == 0) {
    $target_ori = array_column($origin, $target_attribute_name);
    $values = array_count_values($target_ori);
    $max_idx = array_search(max(array_values($values)), $values);
    return $values[$max_idx];
  } elseif (count($features) == 0) {
    return $parent_node_class;
  } else {
    $target_ori = array_column($origin, $target_attribute_name);
    $values = array_count_values($target_ori);
    $parent_node_class = array_search(max(array_values($values)), $values);
    $item_values = [];
    foreach ($features as $feature) {
      $gain = info_gain($data, $feature, $target_attribute_name);
      $split = split_info($data, $feature, $target_attribute_name);
      $item_values[] = $split != 0 ? $gain / $split : 0;
    }
    // split info
    // gain ratio
    $best_feature_index = array_search(max($item_values), $item_values);
    $best_feature = $features[$best_feature_index];
    $tree = [$best_feature => []];
    $new_features = [];
    foreach ($features as $feature) {
      if ($feature != $best_feature)
        $new_features[] = $feature;
    }
    $data_best = array_column($data, $best_feature);
    foreach (array_unique($data_best) as $v) {
      $sub_data = array_filter($data, function ($value) use ($v, $best_feature) {
        return $value[$best_feature] == $v;
      });
      $subtree = c45($sub_data, $origin, $new_features, $target_attribute_name, $parent_node_class);
      $tree[$best_feature][$v] = $subtree;
    }
    return $tree;
  }
}

function trimming($tree)
{
  $new_tree = [];
  foreach ($tree as $t) {
  }
}

function predict($query, $tree, $default = 1)
{
  $query_keys = array_keys($query);
  $tree_keys = array_keys($tree);
  $result = null;
  foreach ($query_keys as $q) {
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

function train_test_split($data)
{
}

define("T", "T");
define("B", "B");
$tree = c45($dataset, $dataset, $g_features);
pprint($tree);
file_put_contents('tree.data', serialize($tree));
file_put_contents('tree.json', json_encode($tree));
