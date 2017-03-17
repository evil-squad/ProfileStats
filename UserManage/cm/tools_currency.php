<?php

	require_once "ui_select.php";
	require_once "ui_table.php";

	function echo_currency_select($label)
	{
		$s=new UISelect($label);

		$s->SetSelected($_SESSION['currency']);		//设定当前营业厅主要货币为默认选中货币

		$s->start();
			$s->option("AED","United Arab Emirates Dirham");
			$s->option("AFN","Afghanistan Afghani");
			$s->option("ALL","Albania Lek");
			$s->option("AMD","Armenia Dram");
			$s->option("ANG","Netherlands Antilles Guilder");
			$s->option("AOA","Angola Kwanza");
			$s->option("ARS","Argentina Peso");
			$s->option("AUD","Australia Dollar");
			$s->option("AWG","Aruba Guilder");
			$s->option("AZN","Azerbaijan New Manat");
			$s->option("BAM","Bosnia and Herzegovina Convertible Marka");
			$s->option("BBD","Barbados Dollar");
			$s->option("BDT","Bangladesh Taka");
			$s->option("BGN","Bulgaria Lev");
			$s->option("BHD","Bahrain Dinar");
			$s->option("BIF","Burundi Franc");
			$s->option("BMD","Bermuda Dollar");
			$s->option("BND","Brunei Darussalam Dollar");
			$s->option("BOB","Bolivia Boliviano");
			$s->option("BRL","Brazil Real");
			$s->option("BSD","Bahamas Dollar");
			$s->option("BTN","Bhutan Ngultrum");
			$s->option("BWP","Botswana Pula");
			$s->option("BYR","Belarus Ruble");
			$s->option("BZD","Belize Dollar");
			$s->option("CAD","Canada Dollar");
			$s->option("CDF","Congo/Kinshasa Franc");
			$s->option("CHF","Switzerland Franc");
			$s->option("CLP","Chile Peso");
			$s->option("CNY","China Yuan Renminbi");
			$s->option("COP","Colombia Peso");
			$s->option("CRC","Costa Rica Colon");
			$s->option("CUC","Cuba Convertible Peso");
			$s->option("CUP","Cuba Peso");
			$s->option("CVE","Cape Verde Escudo");
			$s->option("CZK","Czech Republic Koruna");
			$s->option("DJF","Djibouti Franc");
			$s->option("DKK","Denmark Krone");
			$s->option("DOP","Dominican Republic Peso");
			$s->option("DZD","Algeria Dinar");
			$s->option("EGP","Egypt Pound");
			$s->option("ERN","Eritrea Nakfa");
			$s->option("ETB","Ethiopia Birr");
 			$s->option("EUR","Euro Member Countries");
			$s->option("FJD","Fiji Dollar");
			$s->option("FKP","Falkland Islands (Malvinas) Pound");
			$s->option("GBP","United Kingdom Pound");
			$s->option("GEL","Georgia Lari");
			$s->option("GGP","Guernsey Pound");
			$s->option("GHS","Ghana Cedi");
			$s->option("GIP","Gibraltar Pound");
			$s->option("GMD","Gambia Dalasi");
			$s->option("GNF","Guinea Franc");
			$s->option("GTQ","Guatemala Quetzal");
			$s->option("GYD","Guyana Dollar");
			$s->option("HKD","Hong Kong Dollar");
			$s->option("HNL","Honduras Lempira");
			$s->option("HRK","Croatia Kuna");
			$s->option("HTG","Haiti Gourde");
			$s->option("HUF","Hungary Forint");
			$s->option("IDR","Indonesia Rupiah");
			$s->option("ILS","Israel Shekel");
			$s->option("IMP","Isle of Man Pound");
			$s->option("INR","India Rupee");
			$s->option("IQD","Iraq Dinar");
			$s->option("IRR","Iran Rial");
			$s->option("ISK","Iceland Krona");
			$s->option("JEP","Jersey Pound");
			$s->option("JMD","Jamaica Dollar");
			$s->option("JOD","Jordan Dinar");
			$s->option("JPY","Japan Yen");
			$s->option("KES","Kenya Shilling");
			$s->option("KGS","Kyrgyzstan Som");
			$s->option("KHR","Cambodia Riel");
			$s->option("KMF","Comoros Franc");
			$s->option("KPW","Korea (North) Won");
			$s->option("KRW","Korea (South) Won");
			$s->option("KWD","Kuwait Dinar");
			$s->option("KYD","Cayman Islands Dollar");
			$s->option("KZT","Kazakhstan Tenge");
			$s->option("LAK","Laos Kip");
			$s->option("LBP","Lebanon Pound");
			$s->option("LKR","Sri Lanka Rupee");
			$s->option("LRD","Liberia Dollar");
			$s->option("LSL","Lesotho Loti");
			$s->option("LYD","Libya Dinar");
			$s->option("MAD","Morocco Dirham");
			$s->option("MDL","Moldova Leu");
			$s->option("MGA","Madagascar Ariary");
			$s->option("MKD","Macedonia Denar");
			$s->option("MMK","Myanmar (Burma) Kyat");
			$s->option("MNT","Mongolia Tughrik");
			$s->option("MOP","Macau Pataca");
			$s->option("MRO","Mauritania Ouguiya");
			$s->option("MUR","Mauritius Rupee");
			$s->option("MVR","Maldives (Maldive Islands) Rufiyaa");
			$s->option("MWK","Malawi Kwacha");
			$s->option("MXN","Mexico Peso");
			$s->option("MYR","Malaysia Ringgit");
			$s->option("MZN","Mozambique Metical");
			$s->option("NAD","Namibia Dollar");
			$s->option("NGN","Nigeria Naira");
			$s->option("NIO","Nicaragua Cordoba");
			$s->option("NOK","Norway Krone");
			$s->option("NPR","Nepal Rupee");
			$s->option("NZD","New Zealand Dollar");
			$s->option("OMR","Oman Rial");
			$s->option("PAB","Panama Balboa");
			$s->option("PEN","Peru Nuevo Sol");
			$s->option("PGK","Papua New Guinea Kina");
			$s->option("PHP","Philippines Peso");
			$s->option("PKR","Pakistan Rupee");
			$s->option("PLN","Poland Zloty");
			$s->option("PYG","Paraguay Guarani");
			$s->option("QAR","Qatar Riyal");
			$s->option("RON","Romania New Leu");
			$s->option("RSD","Serbia Dinar");
			$s->option("RUB","Russia Ruble");
			$s->option("RWF","Rwanda Franc");
			$s->option("SAR","Saudi Arabia Riyal");
			$s->option("SBD","Solomon Islands Dollar");
			$s->option("SCR","Seychelles Rupee");
			$s->option("SDG","Sudan Pound");
			$s->option("SEK","Sweden Krona");
			$s->option("SGD","Singapore Dollar");
			$s->option("SHP","Saint Helena Pound");
			$s->option("SLL","Sierra Leone Leone");
			$s->option("SOS","Somalia Shilling");
			$s->option("SPL","Seborga Luigino");
			$s->option("SRD","Suriname Dollar");
			$s->option("STD","São Tomé and Príncipe Dobra");
			$s->option("SVC","El Salvador Colon");
			$s->option("SYP","Syria Pound");
			$s->option("SZL","Swaziland Lilangeni");
			$s->option("THB","Thailand Baht");
			$s->option("TJS","Tajikistan Somoni");
			$s->option("TMT","Turkmenistan Manat");
			$s->option("TND","Tunisia Dinar");
			$s->option("TOP","Tonga Pa'anga");
			$s->option("TRY","Turkey Lira");
			$s->option("TTD","Trinidad and Tobago Dollar");
			$s->option("TVD","Tuvalu Dollar");
			$s->option("TWD","Taiwan New Dollar");
			$s->option("TZS","Tanzania Shilling");
			$s->option("UAH","Ukraine Hryvnia");
			$s->option("UGX","Uganda Shilling");
			$s->option("USD","United States Dollar");
			$s->option("UYU","Uruguay Peso");
			$s->option("UZS","Uzbekistan Som");
			$s->option("VEF","Venezuela Bolivar");
			$s->option("VND","Viet Nam Dong");
			$s->option("VUV","Vanuatu Vatu");
			$s->option("WST","Samoa Tala");
			$s->option("XAF","Communauté Financière Africaine (BEAC) CFA Franc BEAC");
			$s->option("XCD","East Caribbean Dollar");
			$s->option("XDR","International Monetary Fund (IMF) Special Drawing Rights");
			$s->option("XOF","Communauté Financière Africaine (BCEAO) Franc");
			$s->option("XPF","Comptoirs Français du Pacifique (CFP) Franc");
			$s->option("YER","Yemen Rial");
			$s->option("ZAR","South Africa Rand");
			$s->option("ZMW","Zambia Kwacha");
			$s->option("ZWD","Zimbabwe Dollar");
		$s->end();
	}

	function get_base_rate($source,$target)
	{
		return file_get_contents('http://download.finance.yahoo.com/d/quotes.csv?s='.$source.$target.'=X&f=l1&e=.csv');
	}

    function get_service_hall_cash($sql,$currency)
    {
        if(!$sql)return 0;

        $sql_string='select Number from ServiceHall_Cash where SubCode="'.$_SESSION['service_hall'].'_'.$currency.'"';

//        echo $sql_string;

        $sql_result=$sql->query($sql_string);

        if(!$sql_result)return 0;

        $row=$sql_result->fetch_row();

        return $row[0];
    }

    function save_cash_to_service_hall($sql,$currency,$number)
    {
        if(!$sql)return(null);

        return $sql->query('INSERT INTO ServiceHall_Cash(SubCode,Code,Currency,Number) VALUES("'.$_SESSION['service_hall'].'_'.$currency.'","'.$_SESSION['service_hall'].'","'.$currency.'",'.$number.') ON DUPLICATE KEY UPDATE Number=Number+'.$number);
    }

    function show_cash_from_service_hall($sql,$label)
    {
        $table=new UISQLTable($sql,$label,"ServiceHall_Cash",array("Currency","Number"),'Code="'.$_SESSION['service_hall'].'"');

        $table->echo();
    }
