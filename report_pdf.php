<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$from = $_GET['from'];
$to   = $_GET['to'];

require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$query = mysqli_query($conn, "
    SELECT 
        p.product_name,
        COALESCE(SUM(s.quantity),0) AS total_sold,
        COALESCE((p.price - p.cost) * SUM(s.quantity),0) AS profit,
        MAX(s.sale_date) AS last_sale
    FROM products p
    LEFT JOIN sales s 
        ON s.product_id = p.id
        AND s.sale_date BETWEEN '$from' AND '$to'
    GROUP BY p.id, p.product_name, p.price, p.cost
");

$total_profit = 0;

$html = "
<style>
body { font-family: Arial; font-size: 12px; }
h1 { text-align: center; }
table { width:100%; border-collapse: collapse; }
th { background:#000; color:#fff; padding:8px; }
td { padding:8px; border-bottom:1px solid #ccc; }
.total { font-weight:bold; background:#eee; }
</style>

<h1>Sales Report</h1>
<p style='text-align:center'>
Period: ".date('d M Y',strtotime($from))." - ".date('d M Y',strtotime($to))."
</p>

<table>
<tr>
<th>Product</th>
<th>Total Sold</th>
<th>Profit (RM)</th>
<th>Last Sale</th>
</tr>
";

while ($row = mysqli_fetch_assoc($query)) {
    $total_profit += $row['profit'];
    $date = $row['last_sale'] ? date('d M Y', strtotime($row['last_sale'])) : '-';

    $html .= "
    <tr>
        <td>{$row['product_name']}</td>
        <td>{$row['total_sold']}</td>
        <td>".number_format($row['profit'],2)."</td>
        <td>{$date}</td>
    </tr>";
}

$html .= "
<tr class='total'>
<td>TOTAL</td>
<td></td>
<td>RM ".number_format($total_profit,2)."</td>
<td></td>
</tr>
</table>

<p style='text-align:right;margin-top:20px;'>
Generated on ".date('d M Y h:i A')."
</p>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("sales_report.pdf", ["Attachment"=>false]);
exit;