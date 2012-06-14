<?php

namespace Gitolite;

/**
 * Gitolite ACL Class
 *
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class Acl
{
    private $allowedPermissions = array(
        'R', 'RW', 'RW+', '-', 'RWC', 'RW+C', 'RWD', 'RW+D', 'RWCD', 'RW+CD', 'RWDC', 'RW+DC',
    );
    protected $permission = null;
    protected $refexes = '';
    protected $users = array();
    protected $teams = array();

    /**
     * Set Permission
     *
     * Valids: R, RW, RW+, -, RWC, RW+C, RWD, RW+D, RWCD, RW+CD, RWDC, RW+DC
     *
     * @param string $permission A permission
     *
     * @return Gitolite\Acl
     */
    public function setPermission($permission)
    {
        $permission = (string) $permission;
        if (!in_array($permission, $this->allowedPermissions)) {
            throw new \Exception("Unknow permission '{$permission}'");
        }
        $this->permission = $permission;
        return $this;
    }

    /**
     * Get Permission
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set Refexes
     *
     * @param string $refexes A refex
     *
     * @return Gitolite\Acl
     */
    public function setRefexes($refexes)
    {
        $this->refexes = $refexes;
        return $this;
    }

    /**
     * Get Refexes
     *
     * @return string
     */
    public function getRefexes()
    {
        return $this->refexes;
    }


    /**
     * Set Users
     *
     * @param array $users An array of user objects
     *
     * @return Gitolite\Acl
     */
    public function setUsers(array $users)
    {
        $this->users = array();
        foreach ($users as $user) {
            $this->addUser($user);
        }
        return $this;
    }

    /**
     * Get Users
     *
     * @return array of Users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user
     *
     * @param User $user A user object
     *
     * @return Gitolite\Acl
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }

    /**
     * Set Teams
     *
     * @param array $teams An array of team objects
     *
     * @return Gitolite\Acl
     */
    public function setTeams(array $teams)
    {
        $this->teams = array();
        foreach ($teams as $team) {
            $this->addTeam($team);
        }
        return $this;
    }

    /**
     * Get Teams
     *
     * @return array of Teams
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Add Team
     *
     * @param Team $team A team object
     *
     * @return Gitolite\Acl
     */
    public function addTeam(Team $team)
    {
        $this->teams[] = $team;
        return $this;
    }

    /**
     * Returns acl line
     *
     * Format: <permission> <zero or more refexes> = <one or more users/user teams>
     *
     * @param string $nl Include a new line (default true)
     *
     * @return string
     */
    public function render($nl=true)
    {
        if (null === $this->permission) {
            throw new \Exception("Permission not defined");
        }

        if (count($this->teams) == 0 && count($this->user) == 0) {
            throw new \Exception("No users neither teams defined");
        }

        $teams = array();
        foreach ($this->getTeams() as $team) {
            $teams[] = $team->getFormatedName();
        }

        $users = array();
        foreach ($this->getUsers() as $user) {
            $users[] = $user->getUsername();
        }

        return $this->permission . ' = '
                . implode(' ', $users) . ' '
                . implode(' ', $teams)
                . ($nl ? PHP_EOL : '');
    }

}