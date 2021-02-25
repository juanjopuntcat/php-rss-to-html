<?php
	
	// Define URL where we will get the RSS feed from
	$feeds = array(
		"https://www.example.com/feeds/posts/default?q=&max-results=5&alt=rss"
	);
	
	// Create an array where we will have all our RSS entries
	$entries = array();
	
	// Parse feed with the builtin function simplexml_load_file
	// and then push into $entries for later use.
	foreach($feeds as $feed) {
		$xml = simplexml_load_file($feed);
		$entries = array_merge($entries, $xml->xpath("//item"));
	}
	
	// Sort RSS entries by pubDate (change to the field that
	// refers to the publication date in your feed
	usort($entries, function ($feed1, $feed2) {
		return strtotime($feed2->pubDate) - strtotime($feed1->pubDate);
	});
	
	?>
	
	<?php
	// Go through all entries and print them in HTML.
	// Define foreach statement.
	
	foreach($entries as $entry){
		?>
		<!-- HTML code, change it according to your needs -->
		<article class="list-article">
			<div class="list-article-thumb">
				<a href="<?= $entry->link ?>"> <!-- Link of the entry -->
					<?php
						// Print the image in an HTML img tag. In this case,
						// I am looking for a "src" on the description and,
						// then, I take the second match ($matches[1]) that
						// on my feed corresponds to an image. Change it to
						// meet your needs.
						
						$img = $entry->description; 

						if (preg_match('/src="(.*?)"/', $img, $matches)) {
							$srcImg = $matches[1];
						}
					?>
					<img width="300" height="150" src="<?= $srcImg ?>" class="attachment-onepress-blog-small size-onepress-blog-small wp-post-image" alt="" loading="lazy">
				</a>
			</div>
			<div class="list-article-content">
				<div class="list-article-meta">
					
					<?
							
						// Here, I take the list of all categories and I print them.
						// In this case, I am putting a "/" between the tags, that's
						// why I take the first category out and I do not put a "/"
						// on it. If you don't need this, you can just print all the
						// categories by echoing $categ inside the foreach statement
						
						$isfirst = true;
						foreach ($entry->category as $categ) {
							if($isfirst){
								echo '<a href="https://www.example.com/search/label/' . $categ . '" rel="category tag">' . $categ . '</a>';
								$isfirst = false;
							}
							else{
								echo '<span> / <a href="https://www.example.com/search/label/' . $categ . '" rel="category tag">' . $categ . '</a></span>';
							}
						}
					?>
					
				</div>
				<header class="entry-header">
					<h2 class="entry-title"><a href="<?= $entry->link ?>" rel="bookmark"><?= $entry->title ?></a></h2>
				</header>
				<div class="entry-excerpt">
					<!-- Date is printed in format dd/mm/yyyy to meet my needs (in my case, date in Catalan format.
						 Check https://www.php.net/manual/es/function.strftime.php to change it to another format.  -->
					<p><?= strftime('%d/%m/%Y', strtotime($entry->pubDate)) ?></p>
					<p>
						<?
							$descriptionExcerpt = $entry->description;
							$descriptionExcerptStr = strip_tags($descriptionExcerpt);
							$descriptionExcerptSubStr = substr($descriptionExcerptStr, 0, 150);
							echo $descriptionExcerptSubStr . "…"

						?>
					</p>
				</div>
			</div>
		</article>

		<?php
		// Closing foreach statement.
	}
?>