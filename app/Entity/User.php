<?php

namespace NextPHP\App\Entity;

use NextPHP\Data\Persistence\Entity;
use NextPHP\Data\Persistence\Column;
use NextPHP\Data\Persistence\OneToMany;

#[Entity('users')]
class User
{
    #[Column('INT AUTO_INCREMENT PRIMARY KEY', false)]
    public int $id;

    #[Column('VARCHAR(255)', false)]
    public string $name;

    #[Column('VARCHAR(255)', false)]
    public string $email;

    #[Column('VARCHAR(255)', false)]
    public string $password;

    // Getter ve Setter metodları

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }
}