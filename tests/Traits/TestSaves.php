<?php

namespace Tests\Traits;

trait TestSaves 
{
    protected function assertStore(array $sendData, array $testDatabase, array $testJsonData = null) {
        $response = $this->json('POST', $this->routeStore(), $sendData);
        $response->assertStatus(201);

        if ($response->status() !== 201) {
            throw new \Excepion("Response status must be 201, givem {$response->status()}: {$response->content()}");
        }

        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ['id' => $response->json('id')]);
        $testResponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')] );

        return $response;
    }
}