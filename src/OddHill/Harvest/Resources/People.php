<?php namespace OddHill\Harvest\Resources;

use DateTime;

class People extends AbstractResource {

    /**
     * Get a list of all users.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#show-all-users
     *
     * @param  DateTime $updatedSince
     * @return mixed
     *
     * @todo Implement the datetime option.
     */
    public function all(DateTime $updatedSince = null)
    {
        return $this->client->getHttpClient()->get('/people');
    }

    /**
     * Find the specified user.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#show-a-user
     *
     * @param  integer $userId
     *
     * @return mixed
     */
    public function find($userId)
    {
        return $this->client->getHttpClient()->get('/people/' . $userId);
    }

    /**
     * Create a new user.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#create-new-user
     *
     * @param  string $firstname
     * @param  string $lastname
     * @param  string $email
     * @param  string $timezone
     * @param  string $telephone
     * @param  bool   $isAdmin
     *
     * @return mixed
     */
    public function create($firstname, $lastname, $email, $timezone, $telephone, $isAdmin = false)
    {
        $user = [
            'user' => [
                'first-name'    => $firstname,
                'last-name'     => $lastname,
                'email'         => $email,
                'timezone'      => $timezone,
                'is-admin'      => $isAdmin,
                'telephone'     => $telephone
            ],
        ];

        return $this->client->getHttpClient()->post('/people', ['body' => $user]);
    }

    /**
     * Update an existing users account. Right now all parameters must be set
     * while updating. If you do not wish to change a field just pass in the
     * old users credentials.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#update-user
     *
     * @param  integer $userId
     * @param  string  $firstname
     * @param  string  $lastname
     * @param  string  $email
     * @param  string  $timezone
     * @param  string  $telephone
     * @param  bool    $isAdmin
     *
     * @return mixed
     */
    public function update($userId, $firstname, $lastname, $email, $timezone, $telephone, $isAdmin = false)
    {
        $user = [
            'user' => [
                'first-name'    => $firstname,
                'last-name'     => $lastname,
                'email'         => $email,
                'timezone'      => $timezone,
                'is-admin'      => $isAdmin,
                'telephone'     => $telephone
            ],
        ];

        return $this->client->getHttpClient()->put('/people/' . $userId, ['body' => $user]);
    }

    /**
     * Sends a password reset email to the user.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#reset-password-for-user
     *
     * @param  integer $userId
     *
     * @return mixed
     */
    public function resetPassword($userId)
    {
        return $this->client->getHttpClient()->post('/people/' . $userId . '/reset_password');
    }

    /**
     * Delete a users account.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#delete-existing-user
     *
     * @param  integer $userId
     *
     * @return mixed
     */
    public function delete($userId)
    {
        return $this->client->getHttpClient()->delete('/people/' . $userId);
    }

    /**
     * Archive or activet a users account.
     *
     * @link https://github.com/harvesthq/api/blob/master/Sections/People.md#toggle-an-existing-user
     *
     * @param  integer $userId
     *
     * @return mixed
     */
    public function toggle($userId)
    {
        return $this->client->getHttpClient()->post('/people/' . $userId . '/toggle');
    }

} 