<?php

//App::import('Vendor', 'xtcpdf');
require_once(ROOT . DS . 'Vendor' . DS . 'xtcpdf.php');
$pdf = new XTCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Dhruv Patel');
$pdf->SetAuthor('Dhruv Patel');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage('P', 'A4');
$date = date('d-m-Y');
$html = '';
$sr_no = 1;

//pr($data);exit;

$html .= '
<table ><tr><td> Bill No :' . rand(999, 9999) . '</td></tr></table>
<table style="width: 100%;">
		<tr>
			<th align="center"><h2><strong>Presha Software</strong></h2></th>
		</tr>
</table>
<table style="width: 100%;"><tr><td height="10"></td></tr></table>
<table style="width: 100%;">
	<tr>
		<td align="left" width="20%"></td>
		<td align="center" width="60%">
			<h4>
				<strong>
					305-308 Alpha Megacon House,
                    Opp.Armida, Shindhu Bhavan Road,
                    S G Hoghway,Ahmedabad 380054, Gujarat.
				</strong>
			</h4>
		</td>
		<td align="right" width="20%"></td>
	</tr>
</table>
<table style="width: 100%;"><tr><td height="20"></td></tr></table>
<table style="width: 100%;">
	<tr>
		<td align="center" style="text-transform:uppercase;"><h4>';

$html .= 'All Blogs';
$html .= '</h4></td>
	</tr>
</table>
<table style="width: 100%;"><tr><td height="20"></td></tr></table>
<table style="width: 100%;">
	<tr>
		<td>Name  : Dhruv Patel</td>
		<td align="right">Date : ' . $date . '</td>
	</tr>
	<tr>
		<td>Mobile  : 9898989898</td>
		<td align="right">Reference : Dhruv Patel</td>
	</tr>
</table>
<table style="width: 100%;">
	<tr>
		<td>Address : 305-308 Alpha Megacon House,<br/>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Opp.Armida, Shindhu Bhavan Road,<br/>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S G Hoghway,Ahmedabad 380054, Gujarat.
        </td>
	</tr>
</table>
<table style="width: 100%;"><tr><td height="20"></td></tr></table>
<table border="1" cellpadding="3" style="width: 100%;">
	<tr>
		<th width="10%">Sr. No</th>
		<th width="10%">Photo</th>
		<th width="10%" align="center">Title</th>
		<th width="70%" align="center">Description</th>
	</tr>
</table>
<table cellpadding="3" style="width: 100%; font-size:8px;">';
foreach ($data as $d) {
    $html .= '
		<tr>
			<td width="10%" style="border-left:1px solid black; border-bottom:1px solid black;"><center>' . $sr_no++ . '</center></td>
			<td width="10%" style="border-left:1px solid black; border-bottom:1px solid black;"><img src="' . PROJECT_URL . 'img/blog/thumbs/' . $d['photo'] . '" alt="test alt attribute" width="50" height="50" border="0" style="margin-left: 50px;" /></td><td width="10%" align="right" style="border-left:1px solid black; border-bottom:1px solid black;">' . $d['title'] . '</td>
			<td width="70%" align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">' . $d['description'] . '</td>			
		</tr>';
}
$html .= '	
</table>
<table border="1" cellpadding="3" style="width: 100%;">
	<tr>
		<th width="10%">Sr. No</th>
		<th width="10%">Photo</th>
		<th width="10%" align="center">Title</th>
		<th width="70%" align="center">Description</th>
	</tr>
</table>
<table style="width: 100%;"><tr><td height="10"></td></tr></table>
<table cellpadding="3" style=" width: 100%; font-size:8px;">
	<tr><td>Company VAT TIN  : <b>1234567890</b> </td></tr>
</table>
<table cellpadding="3" style=" width: 100%; font-size:10px;">
	<tr>
	<td width="4%" ><b>Notice : </b></td>
	<td width="91%" >Please give payment of bill other wise we will take an action. Thank you in advance for your prompt attention to this matter.
		
	</td>
	<td width="5%" >&nbsp;</td>
	</tr>
</table>
<table width="100%"  style=" width: 100%; "><tr><td height="100"></td></tr></table>
<table width="100%" >
	<tr style="text-decoration: overline;">
		<td >Receiver  Signature</td>
		<td align="right">For Presha Software , Authorised Signature</td>
	</tr>
</table>';
//echo $html; exit;
$fileName = "blogLists";
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();
$pdf->Output($fileName . '.pdf', 'D');
