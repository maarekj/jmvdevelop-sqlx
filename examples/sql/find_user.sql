select *
from `user` u
where
    (:id IS NOT NULL AND u.id = :id OR :id IS NULL)
    AND (:email IS NOT NULL AND u.email = :email OR :email IS NULL)
    AND (:name IS NOT NULL AND u.name = :name OR :name IS NULL)

-- @param integer|null :id
-- @param string|null :email
-- @param string|null :name