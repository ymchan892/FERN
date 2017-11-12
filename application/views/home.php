<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>FERN Simple UI</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        {login}
        <br><br>
        {form}
        銀行 :
        <select name="code">{code}</select>
        <select name="type">{type}</select>
        <select name="currency">{currency}</select>
        <input type="numeric" name="exchange" size="7" placeholder="0.276">
        <input type="submit" value="新增">
        {form_end}
        <br>
        <table border="1" width="50%">
          <tr>
            <td align="right">銀行</td>
            <td align="right">類型</td>
            <td align="right">幣別</td>
            <td align="right">匯率</td>
            <td align="right"></td>
          </tr>
          {exchange_data}
          <tr>
            <td align="right">{code} {code_name}</td>
            <td align="right">{type_name}</td>
            <td align="right">{currency}</td>
            <td align="right">{exchange}</td>
            <td align="right"><a href="/home/delete/{guid}">DEL</a></td>
          </tr>
          {/exchange_data}
        </table>

    </body>
</html>
