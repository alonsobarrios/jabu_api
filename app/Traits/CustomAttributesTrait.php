<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait CustomAttributesTrait
{
    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($name, $value)
    {

        if ($value) {
            if ($this->isTimeAttr($name)) {
                return $this->setTimeAttr($name, $value);
            }
            if ($this->isDateAttr($name)) {
                return $this->setDateAttr($name, $value);
            }
            if ($this->isNumberAttr($name)) {
                return $this->setNumberAttr($name, $value);
            }
            if ($this->isDateTimeAttr($name)) {
                return $this->setDateTimeAttr($name, $value);
            }
            if ($this->isUpperAttr($name)) {
                return $this->setUpperAttr($name, $value);
            }
            if ($this->isFileAttr($name)) {
                return $this->setFileAttr($name, $value);
            }
            if ($this->isDoubleSpace($name)) {
                return $this->replaceDoubleSpaces($name, $value);
            }
        }

        return parent::setAttribute($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isTimeAttr($name)
    {
        return is_array($this->time_fields ?? null) &&
            in_array($name, $this->time_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isDateAttr($name)
    {

        return is_array($this->dates ?? null) &&
            in_array($name, $this->dates);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isDateTimeAttr($name)
    {
        return is_array($this->date_time_fields ?? null) &&
            in_array($name, $this->date_time_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isNumberAttr($name)
    {
        return is_array($this->number_format_fields ?? null) &&
            in_array($name, $this->number_format_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isUpperAttr($name)
    {
        return is_array($this->upper_fields ?? null) &&
            in_array($name, $this->upper_fields);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isFileAttr($name)
    {
        return is_array($this->file_fields ?? null) &&
            in_array($name, $this->file_fields, true);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isDoubleSpace($name)
    {
        return is_array($this->no_double_spaces ?? null) &&
            in_array($name, $this->no_double_spaces);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected function setTimeAttr($name, $value)
    {
        if ($value instanceof Carbon || $value instanceof \DateTime) {
            $this->attributes[$name] = $value->format('H:i');
        } else if (($time = parse_user_time($value)) || ($time = parse_time($value))) {
            $this->attributes[$name] = $time->format('H:i');
        }

        return $this;
    }

    /**
     * @param string $name
     * @param $value
     * @return mixed
     */
    protected function setDateAttr($name, $value)
    {
        if ($value instanceof Carbon || $value instanceof \DateTime) {
            $this->attributes[$name] = $value->format($this->getDateFormat());
        } else if (
            ($date = parse_user_date($value)) ||
            ($date = parse_date($value)) ||
            ($date = parse_date($value, 'Y-m-d H:i:s'))
        ) {
            $this->attributes[$name] = $date->format($this->getDateFormat());
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    protected function setDateTimeAttr($name, $value)
    {

        if ($value instanceof Carbon || $value instanceof \DateTime) {
            $this->attributes[$name] = $value->format($this->getDateFormat());
        } else if (($date = parse_date($value, config('app.date_time_format', 'Y/m/d H:i')))) {
            $this->attributes[$name] = $date->format($this->getDateFormat());
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    protected function setNumberAttr($name, $value)
    {
        $value = str_replace(config('app.number_grp', '.'), '', $value);
        $value = str_replace(config('app.number_dec_sep', ','), '.', $value);
        $this->attributes[$name] = (float)$value;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return CustomAttributesTrait
     */
    protected function setUpperAttr($name, $value)
    {
        $this->attributes[$name] = Str::upper($value);
        return $this;
    }

    /**
     * @param $name
     * @param $file
     * @return $this
     */
    protected function setFileAttr($name, $file)
    {
        if ($file instanceof UploadedFile) {
            if (isset($this->attributes[$name])) {
                Storage::delete($this->attributes[$name]);
            }
            $path = $file->store($this->getTable());
            $this->attributes[$name] = $path;
        }
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return CustomAttributesTrait
     */
    protected function replaceDoubleSpaces($name, $value)
    {
        $this->attributes[$name] = str_replace("  ", " ", $value);
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getAttributeValue($name)
    {
        $value = parent::getAttributeValue($name);
        if ($this->isTimeAttr($name) && is_string($value)) {
            return Carbon::createFromTimeString($value, config('app.timezone'));
        }
        if ($this->isDateAttr($name) && is_string($value)) {
            return Carbon::createFromFormat($this->getDateFormat(), $value, config('app.timezone'));
        }
        if ($this->isUpperAttr($name)) {
            return Str::upper($value);
        }
        if ($this->isDateTimeAttr($name) && is_string($value)) {
            return Carbon::createFromFormat($this->getDateFormat(), $value, config('app.timezone'));
        }
        if ($this->isDoubleSpace($name)) {
            return str_replace("  ", " ", $value);
        }

        return $value;
    }
}