<?php 
	use TableReader\Config\CharRange;
	use TableReader\Config\Downloader;
	
	$config = [
	    'touring' => function() {
			return [
				'src' => Downloader::download(
					"https://docs.google.com/spreadsheets/d/e/2PACX-1vRZxwGM7K-hxaO6g4VTLG4Dmti_I2mtTWWYhDUsLt8I6XpqO-qES0EF7vqcH4YpFQ3fykg6ZcneiwJr/pub?output=xlsx",
					__DIR__ . '/ressource/touring.xlsx'
				),
				
				'filter' => [
					'row' => [[1, 32], [1, 1]],
					'col' => [CharRange::char_range('A', 'AA'), ['B', 'B']],
					'check-char' => ['X', 'R']
				],

				'sort' => [
					'type_dechet'    => 'SHEET-NAME',
					'ref_calendrier' => ['COL' => 0],
					'date_passage'   => ['COL' => 1]
				],

				'action' => [
					'date_passage' => function($value) {
						$dateExcel = (int) $value;
						return date('Y-m-d', ($dateExcel - 25569)*24*60*60);
					}
				]
			];
		},

		'street-listing' => function() {
			return [
				'src' => Downloader::download(
					"https://docs.google.com/spreadsheets/d/e/2PACX-1vRiy1Lhf3lF_eMeE_A42xDOLb6sCtc5KM0CNDc9KjpnYlEXDaJXtFM-5z7JoLzjpG8HQM3ge7NpDhVT/pub?gid=493275478&single=true&output=csv",
					__DIR__ . '/ressource/street-listing.csv'
				),
				
				'filter' => [
					'sheet-name' => ['Worksheet'],
					'row' => [7, 'TOTAL-ROWS'],
					'col' => array_merge(['D'], CharRange::char_range('L', 'N'))
				],

				'sort' => [
					'ref_calendrier' => ['COL' => 0],
					'type_voie'      => ['COL' => 1],
					'intitule_voie'  => ['COL' => 2],
					'quartier'       => ['COL' => 3]
				]
			];
		}
	];