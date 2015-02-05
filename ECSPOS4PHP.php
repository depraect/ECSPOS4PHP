<?php
/**
 * 
 * @author      Edgar Godoy Depraect 
 * @version     1.0
 * @date        08/10/2013
 * 
 */
class ECSPOS4PHP {
    
    
    
    private $Ticket;
    
    function __construct($charset=12) {
        $this->Ticket = '';
        $this->Ticket .= $this->InitializePrinter();
        $this->Ticket .= $this->SetCharset($charset);
    }
    
   
   
    /**
     * Actives the printer buzzer
     */
    function HorizontalTab($pos=0){
            $this->Ticket .= chr(9);
    }
    function SetHorizontalTabPos($n, $k){
        $this->Ticket .= chr(27) .chr(68) .chr($n) .chr($k);
        
    }
    
    function SetPos($col){
        $this->Ticket .= chr(27) .chr(36) . $col ;
        
    }
    
    
    function Cell($size,$text,$align,$ln=false){
        
        $text = substr($this->normalize(trim($text)),0,$size);
        switch ($align){
            case  'L':
                $text = str_pad($text,$size,' ',STR_PAD_RIGHT);
                break;
            case 'C':
                $text = str_pad($text,$size,' ',STR_PAD_BOTH);
                break;
            case 'R':
                $text = str_pad($text,$size,' ',STR_PAD_LEFT);
                break;
        }
        $this->Ticket .= $text;
        if($ln){ $this->Ln(1);}
    }
    
    
    function Text ($text, $ln=false){
        $this->Ticket .= $this->normalize($text);
        if($ln){
            $this->Ln(1);
        }
    }
    
    /*
     * n = 0;Character A（12*24）is selected;
     * n = 1;Character B（8*16）is selected.
     */
    
    function SetFont($n=0){
        $this->Ticket .= chr(27) . chr(77) . chr($n);
    }


    /*
     * Initialize the printer to the state when the printer was turn on.
     * ESC @
     */
    function InitializePrinter(){
        $this->Ticket .=  chr(27). chr(64); 
    }
    
    /**
     * 
     * $type 
     * 
     * 2: EAN13
     * 3: EAN8
     * 4: CODE39
     * 
     * 
     */
    
    function BarCode($code, $type){
        $this->Ticket .= chr(29) .chr(104). chr(80); // PRINT #1, CHR$(&H1D);"h";CHR$(80); 
        $this->Ticket .= chr(29) .chr(119). chr(3); //PRINT #1, CHR$(&H1D);"w";CHR$(3); 
        $this->Ticket .= chr(29) .chr(107). chr($type); //PRINT #1, CHR$(&H1D);"k";CHR$(2); 
        $this->Ticket .= $code . chr(0);
        $this->Ln(1);
        $this->Ticket .= $code;
    }
    
    
    
    function Output($copies=1){
        for($i=1;$i<=$copies;$i++){
            $output .= $this->Ticket;
            $output .=  chr(29) . chr(86) . chr(0) . chr(49);
        }
        return $output;
    }
    
    /*
     * Description:
     * Selects the international character set according the value of n as shown in the follow.
     * 0: USA  1: France 2: Germany 3:U.K 4: Denmark I 5: Sweden 6: Italy 7: 
     * Spain I 8:Japan 9:Norway
     * 10: Denmark II 11Spain II 12: Latin America 13: Korea
     */
    
    function SetCharset($charset=12){
        $this->Ticket .= chr(27) . chr(82) . chr($charset);
    }
    
    /**
     *   Width selection 
     *      0=1,
     *      16=2, 
     *      32=3,
     *      48=4,
     *      64=5,
     *      80=6,
     *      96=7,
     *      112=8
     *   Heigth Selection 0,1,2,3,4,5,6,7
     * 
     *   width + height
     * 
     */
    
    function SetFontSize($size){
        
        $this->Ticket .= chr(29) . chr(33) . chr($size);
        
        /*PRINT #1, CHR$(&H1D);"!";CHR$(17);
            AAAAA ← Select quadruple (double-height x double-width)
            PRINT #1, "AAAAA"; CHR$(&HA); BBBBB
            PRINT #1, CHR$(&H1D);"!";CHR$(0);
            PRINT #1, "BBBBB"; CHR$(&HA);*/
        //$this->Ticket .= chr(29) . chr(33) . $size;
    }
   
    
    /**
     * m=0，48;No n parameter, Executes a full cut.
     */
    
    function PaperFullCut(){
        $this->Ticket .= chr(29) . chr(86) . chr(0) . chr(48);
    }
    
    /**
     * m=1，49;No n parameter, Executes a partial cut 
     * (with one point left in the middle).
     */    
    function PaperPartialCut(){
        $this->Ticket .= chr(29) . chr(86) . chr(0) . chr(49);
    }
    
    /**
     * m=6，n=0~255;Feed paper to n*(horizontal or vertical motion unit) 
     * and executes a full cut.
     */
    
    function PaperFeedAndFullCut($nLines){
        $this->Ticket .= chr(29) . chr(86) . chr(6) . chr($nLines); 
    }
    
    /**
     * 
     * m=66，n=0~255;Feed paper to 
     * n*(horizontal or vertical motion unit) and executes a partial cut.
     */
    
    function PaperFeedAndPartialCut($nLines){
        $this->Ticket .= chr(29) . chr(86) . chr(66) . chr($nLines); 
    }
    
    
    
    function OpenBox(){
        $this->Ticket .=  chr(27). chr(112). chr(48);
    }
    
    /**
     * 
     * 
     */
    function Ln($nLines = 0){
        $this->Ticket .= chr(27). chr(100). chr($nLines);
    }
    
    /**
     *  C : Center
     *  L : Left
     *  R : Rigth
     */
    function TextAlign($pos){
        switch (strtoupper($pos)){
            case 'L' : $this->Ticket .= chr(27). chr(97). chr(0); break;
            case 'C' : $this->Ticket .= chr(27). chr(97). chr(1); break;
            case 'R' : $this->Ticket .= chr(27). chr(97). chr(2); break;
            
        }
    }
    
    function SetLineSpacing($n=0){
        if($n){
            $this->Ticket .= chr(27) . chr(51) .chr($n);
        }else{
            $this->Ticket .= chr(27) .chr(50);
        }
    }
    
    function normalize ($string) {
         $a = 'áéíóúüÁÉÍÓÚÜñ'; 
         $b = '@^{}~`AEIOUU|'; 
        $string = strtr($string, utf8_decode($a), $b);
        $string = utf8_encode($string);
        $string = str_replace('Ñ', chr(92), $string);
    return $string;
}

    
    
    
    
 
    
    
    
    
    
    
    
}

?>
