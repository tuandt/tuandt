<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/17/13
 */
namespace Core\Model;

class ProductTypeTable extends AbstractModel
{
    public function getEntry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('type_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    public function save(ProductType $entry)
    {
        $data = (array) $entry;
        $id = (int)$entry->type_id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getEntry($id)) {
                return $this->tableGateway->update($data, array('type_id' => $id));
            } else {
                throw new \Exception('Data does not exist.');
            }
        }
    }
    public function deleteEntry($id)
    {
        return $this->tableGateway->delete(array('type_id' => $id));
    }
    /**
     * Get type name by type id
     * @param $id
     * @return mixed
     */
    public function getTypeNameById($id)
    {
        $entry = $this->getEntry($id);
        if(!empty($entry)){
            return $entry->name;
        }
        return;
    }

    /**
     * Get type name by id
     * @param $name
     * @return array|\ArrayObject|null
     */
    public function getEntryByName($name)
    {
        $name  = (string) $name;
        $rowset = $this->tableGateway->select(array('name' => $name));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }

    /**
     * @param $id
     * @return bool
     */
    public function clearProductType($id)
    {
        if($this->deleteEntry($id)){
            $success = true;
            $productTable = $this->serviceLocator->get('TdmProductTable');
            if($productTable->checkHasRowHasTypeId($id)){
                $success = $success && $productTable->productTypeDeleted($id);
            }
            return $success;
        }else{
            return false;
        }
    }
}