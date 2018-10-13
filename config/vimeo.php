<?php

/**
 *   Copyright 2018 Vimeo.
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */
declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Vimeo Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'client_id' => 'f4100fe411c4692d3e0432ec6025d82ef90361ac',
            'client_secret' => 'Ey6Qje3pRme3tCpvWk6OQ9sGkm9xRLvGfx0TDgCi0pBd/RhZkH0HZFOQEg+fFo8lm6xXT/1cIriOxAht2zpKbCzIMBtmTaL1YZ/NgjpdJ6HFRLG5fqMuBLICW89ITSJP',
            'access_token' => '6e3da4990fe8bb92e9a73135fedb8e2c',
        ],

        'alternative' => [
            'client_id' => 'dc22050cd784a32b0f729cff3ddf3dc4bb21f484',
            'client_secret' => '7CUWaHjQ9bhJ9e3+FODxEBFTuNm0sZpSNcajw3yq2/eCwrHppN2amAE8TEoTHeAbQe7yRxo+fjFyQlZQljkDmK0WqVP6JnwRKBfTqt7Z8gzSdRfhsxJeozNujEVTMeZ1',
            'access_token' => '5aa16f878c78cc47ed3d4432a1bf1a1a',
        ],

    ],

];
