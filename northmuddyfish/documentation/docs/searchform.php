<form role="search" method="get" class="search-form" autocomplete="off" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="sr-only">
            <label for="keyword">Search for:</label>
        </span>
        <input type="search" class="search-field" id="keyword" placeholder="Search..." title="Search for:"
            value="<?php echo get_search_query() ?>" name="s" />
        <div id="datafetch" class="autocomplete"></div>
        <div class="icon-plus close"></div>
    </label>
    <button class="button button--submit">
        <span class="sr-only">
            <input type="submit" id="searchsubmit" value="Search" />
            <span class="icon-search"></span>
    </button> 
</form>
