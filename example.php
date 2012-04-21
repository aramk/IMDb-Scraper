<?php

/* IMDbScraper Examples By Aram Kocharyan ( akarmenia@gmail.com / twitter.com/akarmenia )

Uncomment the example lines you want to try, var_dump() will print the output which is useful
for arrays, but it's not required for using in your code. When running the script in a browser,
switch to source code view to see the output correctly.

The simplest example to use in your own code is: */

	// Import required files
	require_once('imdb_scraper.php');
	require_once('csv.php'); // CSV helper functions
	
	try {
		$output = IMDbScraper::get('inception');
		var_dump($output);
	} catch (Exception $e) {
		var_dump($e);
	}

/* This will return a key-value array, or FALSE if something hairy comes up. */

/* SIMPLE SEARCH FUNCTIONS */

	/* Find full info using title, year is optional */
		// var_dump( IMDbScraper::get('spongebob movie', '1999') );
	
	/* Find id and title of best search result, optional year */
		// var_dump( IMDbScraper::find('spongebob movie', '1999') );
		// var_dump( IMDbScraper::find('spongebob movie') );
	
	/* Return search results for title query, optional year */
		// var_dump( IMDbScraper::search('spongebob') );
		// var_dump( IMDbScraper::search('spongebob', '1999') );

/* SAVING TO / READING FROM CSV FILE */

	/* Write to CSV */
		// var_dump( csv_write(IMDbScraper::search('spongebob'), 'example_files/example.csv') );
	/* Read from CSV */
		// var_dump( csv_read('example_files/example.csv') );

/* ADVANCED SEARCH FUNCTIONS */

	/* Scrape information from a saved IMDb search page, given as an HTML string */
		// var_dump( IMDbScraper::scrape_search( file_get_contents('example_files/saved_search.html')) );
	
	/* Scrape information from a saved IMDb title page, given as an HTML string */
		// var_dump( IMDbScraper::scrape_info( file_get_contents('example_files/saved_title.html')) );

?>