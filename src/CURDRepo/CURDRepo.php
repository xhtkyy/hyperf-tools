<?php

namespace Xhtkyy\HyperfTools\CURDRepo;


use Hyperf\Database\Model\Builder;
use Psr\Http\Message\RequestInterface;
use Xhtkyy\HyperfTools\App\Exception\AppException;
use Xhtkyy\HyperfTools\App\Exception\NoFoundException;
use Xhtkyy\HyperfTools\App\Exception\ValidationException;

abstract class CURDRepo {
    protected string $model;
    protected array $where = [];
    protected array $betweenWhere = [];
    protected array $params = [];
    protected array $select = [];
    protected array $with = [];

    public function params($params = null): static {
        $this->params = $params ?? (empty($this->params) ? di(RequestInterface::class)->all() : $this->params);
        return $this;
    }

    public function select(string|array $selects): static {
        $this->select = is_array($selects) ? $selects : explode(",", $selects);
        return $this;
    }

    public function with(array $with): static {
        $this->with = $with;
        return $this;
    }

    public function query(): Builder {
        return (new $this->model)->newQuery();
    }

    public function where(...$args): static {
        $this->where[] = func_get_args();
        return $this;
    }

    public function eqWhere(string|array $fields, string $symbol = "="): static {
        $this->params();
        $fields = is_string($fields) ? explode(",", $fields) : $fields;
        foreach ($fields as $field) {
            if (isset($this->params[$field]) && $this->params[$field] != "") {
                $this->where[] = [$field, $symbol, $this->params[$field]];
            }
        }
        return $this;
    }

    public function likeWhere(string|array $fields, string $type = "all"): static {
        $this->params();
        $fields = is_string($fields) ? explode(",", $fields) : $fields;
        foreach ($fields as $field) {
            if (isset($this->params[$field]) && $this->params[$field] != "") {
                $this->where[] = [$field, "like", match ($type) {
                    "right" => "{$this->params[$field]}%",
                    "left" => "%{$this->params[$field]}",
                    default => "%{$this->params[$field]}%",
                }];
            }
        }
        return $this;
    }

    public function betweenWhere(string|array $fields): static {
        $this->params();
        $fields = is_string($fields) ? explode(",", $fields) : $fields;
        foreach ($fields as $field) {
            if (isset($this->params[$field]) && !empty($this->params[$field])) {
                $this->betweenWhere[$field] = $this->params[$field];
            }
        }
        return $this;
    }

    /**
     * 创建
     * @param $params
     * @return int
     */
    public function create($params): int {
        if (isset($params['id'])) unset($params['id']);
        return $this->query()->create($params)->id ?? 0;
    }

    /**
     * 修改
     * @param array $params
     * @param array $where
     * @return bool|int
     * @throws AppException
     */
    public function modify(array $params, array $where = []): bool|int {
        $where = array_merge($this->where, $where);
        if (empty($where)) throw new ValidationException("修改条件不能为空");
        $info = $this->query()
            ->where($where)
            ->first();
        if (!$info) throw new NoFoundException("查询失败,稍后重试");
        //过滤掉id
        if (isset($params["id"])) unset($params["id"]);
        return tap($info->update($params) ?? 0,
            function () {
                $this->clear();
            }
        );
    }

    /**
     * 删除
     * @param $id
     * @param array $where
     * @return int
     * @throws ValidationException
     */
    public function delete($id = null, array $where = []): int {
        if ($id) $where["id"] = $id;
        $where = array_merge($this->where, $where);
        if (empty($where)) throw new ValidationException("删除条件不能为空");
        return $this->query()
            ->where($where)
            ->delete();
    }

    /**
     * 获取详情
     * @param $id
     * @param array $where
     * @return object|null
     * @throws ValidationException
     */
    public function detail($id = null, array $where = []): object|null {
        if ($id) $where["id"] = $id;
        $where = array_merge($this->where, $where);
        if (empty($where)) throw new ValidationException("条件不能为空");
        $query = $this->query();
        if (!empty($this->with)) {
            $query->with($this->with);
        }
        return tap($query->where($where)->first(), function ($detail) {
            $this->clear();
        });
    }

    /**
     * @param array $where
     * @param array|string $select
     * @param string|null $orderBy
     * @return array
     */
    public function getList(array $where = [], array|string $select = [], string $orderBy = null): array {
        $query = $this->query();
        $where = array_merge($this->where, $where);
        !empty($where) && $query->where($where);
        $select = array_merge($this->select, is_array($select) ? $select : explode(",", $select));
        !empty($select) && $query->select($select);

        if (!empty($this->betweenWhere)) {
            foreach ($this->betweenWhere as $field => $value) {
                $query->whereBetween($field, is_array($value) ? $value : explode(",", $value));
            }
        }

        if (!empty($this->with)) {
            $query->with($this->with);
        }
        $count = $query->count();
        if (isset($this->params['page'])) {
            list($page, $size) = [$this->params['page'] ?? 1, $this->params['pageSize'] ?? 10];
            $query->skip(($page - 1) * $size)->take($size);
        }
        $list = $query->orderByDesc($orderBy ?? 'id')->get();
        return tap(compact("count", "list"), function () {
            $this->clear();
        });
    }

    public function clear(): void {
        $this->where        = [];
        $this->betweenWhere = [];
        $this->select       = [];
        $this->params       = [];
        $this->with         = [];
    }

    public function value($id, $field) {
        return $this->query()->where("id", "=", $id)->value($field);
    }

    public function checkFieldOnly($field, $value, $where = null): bool {
        $query = $this->query();
        if ($where) $query->where($where);
        return !!$query->where($field, "=", $value)->selectRaw("1")->first();
    }
}