/**
 * Base Repository Class
 *
 * @param   object  properties
 * @constructor
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$MRA.Models.BaseRepository = function() {
    this.collection = {};
};

/**
 * Add an entity to the repository
 *
 * @param   object  entity
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$MRA.Models.BaseRepository.prototype.add = function(entity) {
    this.collection[entity.id] = entity;
};

/**
 * Retrieve an entity from the repository.
 *
 * Alias for this.collection[id].
 *
 * @param   int     id
 * @return  $MRA.Models.BaseModel
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$MRA.Models.BaseRepository.prototype.get = function(id) {
    return this.collection[id];
};


