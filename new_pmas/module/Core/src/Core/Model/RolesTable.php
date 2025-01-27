<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/17/13
 */
namespace Core\Model;

use Core\Model\AbstractModel;

class RolesTable extends AbstractModel
{
    public function getEntry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('role_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    public function save(Roles $entry)
    {
        $data = (array) $entry;

        $id = (int)$entry->role_id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getEntry($id)) {
                return $this->tableGateway->update($data, array('role_id' => $id));
            } else {
                throw new \Exception('Data does not exist.');
            }
        }
    }

    /**
     * Get Role name by id
     * @param $id
     * @return mixed
     */
    public function getRoleNameById($id)
    {
        if($entry = $this->getEntry($id)){
            return $entry->role;
        }
        return;
    }

    /**
     * @param $id
     * @return null
     */
    public function getResourcesById($id)
    {
        if($entry = $this->getEntry($id)){
            return $entry->resource_ids;
        }
        return null;
    }
    /**
     * @return null|\Zend\Db\ResultSet\ResultSet
     */
    public function getAvaiableRoles()
    {
        $rowset = $this->tableGateway->select();
        if ($rowset->count() <= 0) {
            return null;
        }
        return $rowset;
    }
    /**
     * @param $id
     * @return int
     */
    public function deleteEntry($id)
    {
        return $this->tableGateway->delete(array('role_id' => $id));
    }
}