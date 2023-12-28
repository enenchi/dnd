
class TableRoller {
    static LIGHT_BLUE = "rgba(72, 17, 250, 0.2)";

    /**
     * @param table_id - the id of the table in css format
     * @param chance_class - the class of the elements with the chances in the
     *      table in css format
     */
    constructor(table_id, chance_class, body_idx, selected_color, min_iters=3, max_iters=6, min_ms=10, max_ms=200) {
        // set roller variables
        this.table_id = table_id;
        this.chance_class = chance_class;
        this.body_idx = body_idx;
        this.selected_color = selected_color;
        this.min_iters = min_iters;
        this.max_iters = max_iters;
        this.min_ms = min_ms;
        this.max_ms = max_ms;
        let body = document.querySelector(table_id).tBodies[body_idx];
        this.rows = body.rows;
        // set variables for tracking each roll
        this.timeout = null;
        this.rowsLeft = 0;
        this.prevColor = null;
        this.currentRow = -1;
        this.ms = 0;
        this.ms_inc = 0;
        // get the chances
        let lowest = null;
        let highest = null;
        let ranges = [];
        let chances = body.querySelectorAll(chance_class);
        for (let c = 0; c < chances.length; c++) {
            let range = chances[c].innerText.split("-");
            let low = parseInt(range[0]);
            let high = low;
            if (range.length > 1) {
                high = parseInt(range[1]);
            }
            if (lowest === null || low < lowest) {
                lowest = low;
            }
            if (highest === null || high > highest) {
                highest = high;
            }
            ranges.push([low, high]);
        }
        this.ranges = ranges;
        this.die = [lowest, highest];
        // set wrapper function for this table
        this.nextRowWrapper = () => {this.nextRow()};
    }

    roll() {
        // clear timeout and reset color
        if (this.timeout != null) {
            clearTimeout(this.timeout);
            this.timeout = null;
        }
        if (this.currentRow >= 0) {
            this.rows[this.currentRow].style.backgroundColor = this.prevColor;
        }
        // clear tracking variables
        this.currentRow = -1;
        this.rowsLeft = 0;
        this.ms = this.min_ms;
        // roll the table
        let stopRow = -1;
        let rollRes = Math.floor(Math.random() * this.die[1] - this.die[0] + 1) + this.die[0];
        for (let i = 0; i < this.ranges.length; i++) {
            if (this.ranges[i][0] <= rollRes && rollRes <= this.ranges[i][1]) {
                stopRow = i;
                break;
            }
        }
        console.log("Roll result: " + rollRes);
        // add random number of times to loop through the table
        this.rowsLeft = stopRow + this.rows.length *
            (Math.floor(Math.random() * (this.max_iters - this.min_iters)) + this.min_iters) + 1;
        // set ms and timout
        this.ms_inc = (this.max_ms - this.min_ms) / this.rowsLeft;
        this.timeout = setTimeout(this.nextRowWrapper, this.ms);
    }

    nextRow() {
        if (this.currentRow >= 0) {
            // reset old current row color
            this.rows[this.currentRow].style.backgroundColor = this.prevColor;
        }
        // set new current row color and prev color
        this.currentRow = (this.currentRow + 1) % this.rows.length;
        this.prevColor = this.rows[this.currentRow].style.backgroundColor;
        this.rows[this.currentRow].style.backgroundColor = this.selected_color;
        // update timing
        this.rowsLeft -= 1;
        if (this.rowsLeft > 0) {
            this.ms += this.ms_inc;
            this.timeout = setTimeout(this.nextRowWrapper, this.ms);
        }
    }
}

