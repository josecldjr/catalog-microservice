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

        $this->asserInDatabase($response, $testDatabase);        
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    protected function assertUpdate(array $sendData, array $testDatabase, array $testJsonData = null) {
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        $response->assertStatus(200);

        if ($response->status() !== 200) {
            throw new \Excepion("Response status must be 200, give {$response->status()}: {$response->content()}");
        }

        $this->asserInDatabase($response, $testDatabase);        
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);

        return $response;
    }

    private function asserInDatabase($response, array $testDatabase) {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ['id' => $response->json('id')] );
        
    }

    private function assertJsonResponseContent($response, array $testDatabase, array $testJsonData = null) {
        $testResponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')] );
    }
}