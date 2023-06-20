<?php

require_once('Model.php');

class UserModel extends Model implements Stringable, JsonSerializable {
    public string $email;
    public string $name;
    public string $surname;
    public string $username;
    public string $password;
    public string $last_login_date;
    public string $created_at;
    public string $updated_at;

    public function __toString(): string
    {
        return"
        id={$this->id},
        email={$this->email},
        name={$this->name},
        surname={$this->surname},
        username={$this->username},
        last_login_date={$this->last_login_date},
        created_at={$this->created_at},
        updated_at={$this->updated_at}";
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'username' => $this->username,
            'last_login_date' => $this->last_login_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }


}