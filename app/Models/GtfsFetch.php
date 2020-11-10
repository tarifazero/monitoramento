<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GtfsFetch extends Model
{
    use HasFactory;

    const STORAGE_DISK = 'gtfs';

    const UPDATED_AT = null;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public static function latest()
    {
        return self::orderBy('created_at', 'DESC')->first();
    }

    public function getCalendarDatesFilePath()
    {
        return Storage::disk(self::STORAGE_DISK)->path($this->path . '/calendar_dates.txt');
    }

    public function getRoutesFilePath()
    {
        return Storage::disk(self::STORAGE_DISK)->path($this->path . '/routes.txt');
    }

    public function getTripsFilePath()
    {
        return Storage::disk(self::STORAGE_DISK)->path($this->path . '/trips.txt');
    }

    public function unzip()
    {
        $zip = new ZipArchive;
        $canOpen = $zip->open(Storage::disk(self::STORAGE_DISK)->path($this->path . '.zip'));

        if ($canOpen !== true) {
            throw new \Exception('Could not unzip GTFS file');
        }

        $zip->extractTo(Storage::disk(self::STORAGE_DISK)->path($this->path));
        $zip->close();
    }
}
