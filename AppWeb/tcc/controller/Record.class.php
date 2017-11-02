<?php
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

final class Record
{
	private $uid;
	private $pagina;
	private $maximo = 5;
	private $records;
	
	public function __construct($uid, $pagina)
	{
		DataMapper::init(new PDO('mysql:host=localhost;dbname=faccamp_tcc', 'root', 'devecchi'));
		
		$this->uid = $uid;
		$this->pagina = $pagina;
		$this->records = RecordMapper::get($uid, $pagina);
	}
	
	public function getRecords()
	{
		$retorno  = "<p>Registros dos Horarios:</p>\n";
		$retorno .= "<table>\n";
		$retorno .= "<tr>\n";
		$retorno .= "  <th>Data</th>\n";
		$retorno .= "  <th>Hora</th>\n";
		$retorno .= "  <th>Dia</th>\n";
		$retorno .= "</tr>\n";

		$i = 0;

		foreach ($this->records[1] as $record)
		{
			if ( ($i++ % 2) == 0 )
				$linha = 'linha1';
			else
				$linha = 'linha2';
			
			$retorno .= "<tr class=\"$linha\">\n";
			$retorno .= "  <td>{$this->converteData($record->data_registro)}</td>\n";
			$retorno .= "  <td>{$record->hora_registro}</td>\n";
			$retorno .= "  <td>{$record->dia_registro}</td>\n";
			$retorno .= "</tr>\n";
		}

		$retorno .= "</table>\n";
		
		$menos = $this->pagina - 1;
		$mais  = $this->pagina + 1;
		$pgs   = ceil($this->records[0]->total / $this->maximo);

		if($pgs > 1 ) 
		{
			$retorno .= "<br>\n";

			if($menos > 0)
				$retorno .= "<a href='javascript:pagina(\"{$this->uid}\", {$menos})'>anterior</a>&nbsp; ";

			for( $i = 1; $i <= $pgs; $i++ )
			{
				if($i != $this->pagina)
					$retorno .= " <a href='javascript:pagina(\"{$this->uid}\", {$i})'>$i</a> | ";
				else
					$retorno .= " <strong>".$i."</strong> | ";
			}

			if($mais <= $pgs) 
				$retorno .= " <a href='javascript:pagina(\"{$this->uid}\", {$mais})'>próxima</a>\n";
		}

		return $retorno;
	}
	
	private function converteData($data)
	{
		return implode('/', array_reverse(explode('-', $data)));
	}
}
?>