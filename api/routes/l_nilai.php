<?php
/**
 * Validasi
 * @param  array $data
 * @param  array $custom
 * @return array
 */

/*Step 12*/
$app->get('/l_nilai/data', function ($request, $response) {
    
$nilai= [
    
        '1'      =>
        [
            'nama' => 'Aan',
            'nilai'  =>  [ 
                            [
                            'mapel' => 'IPA',
                            'nilai' => '90'

                            ],
                             [
                            'mapel' => 'IPS',
                            'nilai' => '80'

                            ],
                             [
                            'mapel' => 'MTK',
                            'nilai' => '75'

                            ],
                        ],
        ],
         '2'      =>
        [
            'nama' => 'Ainul',
            'nilai'  =>  [ 
                            [
                            'mapel' => 'IPA',
                            'nilai' => '60'

                            ],
                             [
                            'mapel' => 'IPS',
                            'nilai' => '60'

                            ],
                             [
                            'mapel' => 'MTK',
                            'nilai' => '60'

                            ],
                        ],
        ],
        
         '3'      =>
        [
            'nama' => 'Yakin',
            'nilai'  =>  [ 
                            [
                            'mapel' => 'IPA',
                            'nilai' => '40'

                            ],
                             [
                            'mapel' => 'IPS',
                            'nilai' => '40'

                            ],
                             [
                            'mapel' => 'MTK',
                            'nilai' => '40'

                            ],
                        ],
        ],
        

    
];


$mapel= [
    
        '1'      =>
        [
            'nama' => 'IPA',
            
        ],
         '2'      =>
        [
            'nama' => 'IPS',
            
        ],
        
         '3'      =>
        [
            'nama' => 'MTK',
         
        ],
        

    
];




        return successResponse($response, ['mapel' => $mapel,'nilai' => $nilai ]);
   
});

/*Step 12*/