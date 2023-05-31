<?php

require_once('UserStatistics.php');
require_once('Model.php');

class UserModel extends Model implements Stringable, JsonSerializable {

    public string $email;
    public string $name;
    public string $surname;
    public string $username;
    public string $last_login_date;
    public bool $logged;
    public string $created_at;
    public string $updated_at;
    public UserStatistics $statistics;

    public function __toString(): string
    {
        return"
        email={$this->email},
        name={$this->name},
        surname={$this->surname},
        username={$this->username},
        last_login_date={$this->last_login_date},
        logged={$this->logged},
        created_at={$this->created_at},
        updated_at={$this->updated_at},
        statistics={$this->statistics}";
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'username' => $this->username,
            'last_login_date' => $this->last_login_date,
            'logged' => $this->logged,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'statistics' => $this->statistics,
        ];
    }


}