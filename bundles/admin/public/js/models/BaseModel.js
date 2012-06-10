/**
 * Base Model Class
 *
 * @param   object  properties
 * @constructor
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$MRA.Models.BaseModel = function(properties) {
    // Mass-assign all model properties
    for (property in properties) {
        this[property] = properties[property];
    }

    this.repository.add(this);
};

$MRA.Models.BaseModel.prototype.repository = new $MRA.Models.BaseRepository();
