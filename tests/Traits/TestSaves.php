<?php

namespace Tests\Traits;

trait TestSaves 
{
    protected function assertStore($sendData, $testData) {
        $response = $this->json('POST', $this->routeStore(), $sendData);
        $response->assertStatus(201);

        if ($response->status() !== 201) {
            throw new \Excepion("Response status must be 201, givem {$response->status()}: {$response->content()}");
        }

        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testData + ['id' => $response->json('id')]) ;
    }
}