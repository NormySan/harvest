<?php namespace OddHill\Harvest\Resources;

class TimeTracking extends AbstractResource {

    /**
     * Time tracking endpoint uris.
     */
    const DAILY_URI         = 'daily';
    const DAILY_ENTRY_URI   = 'daily/show/';
    const DAILY_TIMER_URI   = 'daily/timer/';
    const DAILY_ADD_URI     = 'daily/add';
    const DAILY_DELETE_URI  = 'daily/delete/';
    const DAILY_UPDATE_URI  = 'daily/update/';

    /**
     * Get entries for the current day including projects.
     *
     * @param  null|int $day
     * @param  null|int $year
     * @return array
     */
    public function daily($day = null, $year = null)
    {
        $url = self::DAILY_URI;

        // If both day and year are integers get time tracking results for a
        // specific day and year.
        if (is_int($day) && is_int($year))
        {
            $url .= '/' . $day . '/' . $year;
        }

        return $this->get($url);
    }

    /**
     * Retrieve projects from the daily endpoint.
     */
    public function projects()
    {
        $response = $this->get(self::DAILY_URI);

        return $response['projects'];
    }

}
