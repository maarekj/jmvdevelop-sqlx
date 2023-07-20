select
    p.id p_id,
    p.title p_title,
    p.content p_content,
    c.id c_id,
    c.name c_name
from `post` p
left join category c on p.category_id = c.id
limit 100

#
# @param int|null $name