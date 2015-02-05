# ECSPOS4PHP
ECS/POS for PHP

Instrucciones

Constructor
$pos = new ECSPOS4PHP();

Texto sin formato 
$pos->Text("SOME TEXT",true);

Alineación
$pos->TextAlign('L');

Espacio entre lineas
$pos->SetLineSpacing(30);
 
Celda (caracteresde de la celda, texto, alineacion, salto de linea )
$pos->Cell(30, "SOME TEXT" ,'L', true);

Salto de linea
$pos->Ln(3);
 
Codigo de barras
$pos->BarCode($code, 4);
 
Salida
$pos->Output($cant);
 
 

