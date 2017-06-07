<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-03
 */
namespace Exts\ServiceGateway\Models;

use FastD\Http\Response;
use FastD\Model\Model as BaseModel;

abstract class Model extends BaseModel
{

    protected $table;

    protected $primaryKey = 'id';

    protected $perPage = 15;

    public function find($id)
    {
        return $this->db->get($this->table, '*', [
            $this->primaryKey => $id,
        ]);
    }

    public function findOrAbort($id)
    {
        $record = $this->find($id);
        if (!$record) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $record;
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($this->table, $fields, $where);
    }

    public function getOne($where, $fields = '*')
    {
        return $this->db->get($this->table, $fields, $where);
    }

    public function paginate(array $where = [], $fields = '*', $page = null, $perPage = null)
    {
        $page = intval(is_null($page) ? (request()->getQueryParams()['page'] ?? 1) : $page);
        $page <= 0 && $page = 1;

        $perPage = intval(is_null($perPage) ? (request()->getQueryParams()['per_page'] ?? $this->perPage) : $perPage);
        $perPage <= 0 && $perPage = $this->perPage;

        $total = $this->db->count($this->table, $this->primaryKey, $where);

        $where['LIMIT'] = [($page - 1) * $perPage, $perPage];

        if (isset(request()->getQueryParams()['from_id'])) {
            /**
             * 确保在 where 中主键条件在开头
             */
            $where['LIMIT'] = $perPage;
            $where = array_merge(
                [
                    "{$this->primaryKey}[>]" => request()->getQueryParams()['from_id'],
                    'LIMIT'                  => $perPage,
                ],
                $where
            );
        }

        if (isset(request()->getQueryParams()['to_id'])) {
            $where = array_merge(
                [
                    "{$this->primaryKey}[<]" => request()->getQueryParams()['to_id'],
                    'LIMIT'                  => $perPage,
                ],
                $where
            );
        }

        $list = $this->get($where, $fields);

        return [
            'page' => [
                'total'      => $total,
                'per_page'   => $perPage,
                'current'    => $page,
                'total_page' => (int)ceil($total / $perPage),
                'from'       => current($list)[$this->primaryKey],
                'to'         => end($list)[$this->primaryKey],
            ],
            'list'       => $list,
        ];
    }

    public function insert(array $data)
    {
        if (is_null($this->db->insert($this->table, $data))) {
            return false;
        }

        return $this->getDatabase()->id();
    }

    public function update(array $data, array $where)
    {
        return $this->db->update($this->table, $data, $where);
    }

    public function save(array $data, $id)
    {
        return $this->update($data, [
            $this->primaryKey => $id,
        ]);
    }

    public function delete(array $where)
    {
        return $this->db->delete($this->table, $where);
    }

    public function has(array $where)
    {
        return $this->db->has($this->table, $where);
    }

    public function count(array $where)
    {
        return $this->db->count($this->table, $where);
    }
}
