<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $folder = 'tasks';
    
    private function ensureFolderExists(): void
    {
        if (!Storage::exists($this->folder)) {
            Storage::makeDirectory($this->folder);
        }
    }

    public function all(): array
    {
        $this->ensureFolderExists();
        $tasks = [];
        foreach (Storage::files($this->folder) as $file) {
            $tasks[] = json_decode(Storage::get($file), true);
        }
        return $tasks;
    }

    public function create(array $data): array
    {
        $this->ensureFolderExists();
        $id = time();
        $data['id'] = $id;
        Storage::put("{$this->folder}/{$id}.json", json_encode($data, JSON_PRETTY_PRINT));
        return $data;
    }

    public function find(int $id): ?array
    {
        $this->ensureFolderExists();
        $filePath = "{$this->folder}/{$id}.json";
        if (Storage::exists($filePath)) {
            return json_decode(Storage::get($filePath), true);
        }
        return null;
    }

    public function update(int $id, array $data): ?array
    {
        $this->ensureFolderExists();
        $filePath = "{$this->folder}/{$id}.json";
        
        if (!Storage::exists($filePath)) {
            return null;
        }
        
        $currentData = json_decode(Storage::get($filePath), true);
        
        $updatedData = array_merge($currentData, $data);
        
        Storage::put($filePath, json_encode($updatedData, JSON_PRETTY_PRINT));
        
        return $updatedData;
    }

    public function delete(int $id): bool
    {
        $this->ensureFolderExists();
        $filePath = "{$this->folder}/{$id}.json";
        
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return true;
        }
        
        return false;
    }
}