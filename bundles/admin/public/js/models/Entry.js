/**
 * Entry Repository Class
 *
 * @constructor
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$MRA.Models.EntryRepository = new $MRA.Models.BaseRepository();

/**
 * Entry Class
 *
 * Front-end interaction with the Entry model
 *
 * @param	object	properties
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$MRA.Models.Entry = function(properties) {
    this.constructor(properties);
};
$MRA.Models.Entry.prototype = new $MRA.Models.BaseModel();
$MRA.Models.Entry.prototype.constructor = $MRA.Models.BaseModel;

// Use the EntryRepository repository
$MRA.Models.Entry.prototype.repository = $MRA.Models.EntryRepository;
