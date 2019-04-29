<?php
 \backend\modules\askm\assets\web\php\ExcelGrid::widget([ 
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
 			//'extension'=>'xlsx',
 			'filename'=>'DataIB',
 			'properties' =>[
 			//'creator' =>'',
 			//'title'  => '',
 			//'subject'  => '',
 			//'category' => '',
			//'keywords'  => '',
 			//'manager'  => '',
 		],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
 			'dim.nama',
 			'dim.thn_masuk',
 			'desc', 
 			'tujuan',
            'rencana_berangkat',
            'rencana_kembali',
            'realisasi_berangkat',
            'realisasi_kembali',
        ],
    ]);
?>