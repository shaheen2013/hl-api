<?php

namespace App\Repositories\Bookings;

class BookingFunnelTypeInterface
{
    /** @var int $brandID */
    private $brandID;
    /** @var int $userID */
    private $userID;
    /** @var int $referrerID */
    private $referrerID;
    /** @var string $session */
    private $session;
    /** @var string $date */
    private $date;
    /** @var string $bookingAction */
    private $bookingAction;
    /** @var string $source */
    private $source;
    /** @var string $sourceAction */
    private $sourceAction;

    /**
     * BookingFunnelTypeInterface constructor.
     * @param int $brandID
     * @param int|null $userID
     * @param int|null $referrerID
     * @param string $session
     * @param string $date
     * @param string $bookingAction
     * @param null|string $source
     * @param null|string $sourceAction
     */
    public function __construct(int $brandID, ?int $userID, ?int $referrerID, string $session, string $date, string $bookingAction, ?string $source, ?string $sourceAction)
    {
        $this->brandID = $brandID;
        $this->userID = $userID;
        $this->referrerID = $referrerID;
        $this->session = $session;
        $this->date = $date;
        $this->bookingAction = $bookingAction;
        $this->source = $source;
        $this->sourceAction = $sourceAction;
    }

    /**
     * @return array
     */
    public function get()
    {
        return get_object_vars($this);
    }

    /**
     * @param $attribute
     * @param $value
     */
    public function set($attribute, $value)
    {
        $this->{$attribute} = $value;
    }
}
