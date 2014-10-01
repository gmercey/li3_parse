<?php
/**
 * Cinemur parse
 * Copyright (c) 2014 Cinémur S.A. All rights reserved.
 */
?>
<pre>
    <?php print_r($users, true)?>
</pre>

<form method="post" class="form">
    <div class="form-group">
        <label for="verb">Method</label>
        <select id="verb" name="verb">
            <option value="GET">GET</option>
            <option value="POST">POST</option>
            <option value="PUT">PUT</option>
            <option value="DELETE">DELETE</option>
        </select>
    </div>
    <div class="form-group">
        <label for="endpoint">Endpoind</label>
        <input type="text" id="endpoint" name="endpoint" placeholder="/1/functions/hello" class="form-control">
    </div>

    <div class="form-group">
        <label for="params">Parameters</label>

        <div class="row">
            <div class="col-xs-3">
                <input type="text" id="params" name="params[0][key]" class="form-control" placeholder="key">
            </div>
            <div class="col-xs-3">
                <input type="text" id="params" name="params[0][value]" class="form-control" placeholder="value">
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-xs-3">
                <input type="text" id="params" name="params[1][key]" class="form-control" placeholder="key">
            </div>
            <div class="col-xs-3">
                <input type="text" id="params" name="params[1][value]" class="form-control" placeholder="value">
            </div>
        </div>
    </div>

    <input type="submit" class="btn btn-default">
</form>