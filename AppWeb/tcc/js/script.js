/*
 * Projeto:
 *  - Ponto Eletrônico Digital
 *
 * Descrição:
 *  - TCC para a Faccamp Facundade Campo Limpo Paulista,
 *    como requesito parcial para obtenção do título de
 *    Bacharel em Ciência da Computação.
 *
 * criado em 26 Abril 2015
 * by André Devecchi
 * contato: andre.devecchi@gmail.com
 * ----------------------------------------------------------------------------
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Devecchi, André; Silva, André; faccuser@gmail.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

function pagina(uid, pg) {
	if (uid == "") {
		document.getElementById("registro").innerHTML = "<p class=\"normal\">Selecione o colaborador para mostrar os registros dos horarios...</p>";
		return;
	} else { 
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("registro").innerHTML = xmlhttp.responseText;
				document.getElementById("loader").style.display = "none";
			}
		}
		
		xmlhttp.open("GET","getrecords.php?uid="+uid+"&pg="+pg,true);
		xmlhttp.send();
		document.getElementById("loader").style.display = "inline-block";
		document.getElementById("registro").innerHTML = "<p class=\"normal\">Buscando registros...</p>";
	}
}