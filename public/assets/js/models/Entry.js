/**
 * Entry Class
 *
 * Front-end interaction with the Entry model
 *
 * @param	object	properties
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
function Entry(properties) {
	// Mass-assign all model properties
	for (property in properties) {
		this[property] = properties[property];
	}
}
