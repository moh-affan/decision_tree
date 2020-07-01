<html>

<head>
  <title>ID3</title>
</head>

<body>
  <form action="prediksi.php" method="POST">
    <table>
      <?php
      for ($i = 1; $i <= 21; $i++) :
      ?>
        <tr>
          <td width="50">G<?=$i?></td>
          <td width="50"><input type="radio" name="G<?=$i?>" value="B" /> B <input type="radio" name="G<?=$i?>" value="T" /> T</td>
        </tr>
      <?php endfor ?>
      <tr>
        <td><input type="submit" value="Diagnosa" /></td>
      </tr>
    </table>
  </form>
</body>

</html>