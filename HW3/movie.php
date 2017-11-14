<!DOCTYPE html>
<html>

	<!--
	Yu Fu
	CSE 154 AE, Spring 2015
	TA:	Colin Miller
	Assignment 3
	
	tmnt.php
	Extra Feature 3 and 5 are implemented.
	-->

	<head>

		<?php
			# Read the name of the movie to display.
			$movie = $_GET["film"];

			# Read the info file of the movie.
			list($title, $year, $rate) = file("$movie/info.txt", FILE_IGNORE_NEW_LINES);

			# Read the review files of the movie.
			$reviews = glob("$movie/review*.txt");
		?>

		<title>Rancid Tomatoes - <?= $title ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Rating and reviews of movie, <?= $title ?>">
		<meta name="keywords" content="<?= $title ?>, rancid tomatoes, movie, rate, review">
		<link href="movie.css" type="text/css" rel="stylesheet" />
	</head>

	<body>

		<div class="banner">
			<img src="https://webster.cs.washington.edu/images/rancidbanner.png" alt="Rancid Tomatoes" />
		</div>

		<h1><?= $title ?> (<?= $year ?>)</h1>

		<div id="overallContent">

			<?php
				rate_banner($rate);
			?>

			<div id="overallRight">
		
				<div id="overview">
					<img src="<?= $movie ?>/overview.png" alt="general overview" />
				</div>

				<?php 
					overview_list($movie);
				?>

			</div>

			<div id="overallLeft">

				<?php
					review_columns($reviews);
				?>

			</div>

			<p id="overallButtom">(1-<?= count($reviews) ?>) of <?= count($reviews) ?></p>

			<?php
				rate_banner($rate);
			?>
			
		</div>

		<div id="validator">
			<a href="https://webster.cs.washington.edu/validate-html.php"><img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML5" /></a><br />
			<a href="https://webster.cs.washington.edu/validate-css.php"><img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
		</div>

		<div class="banner">
			<img src="https://webster.cs.washington.edu/images/rancidbanner.png" alt="Rancid Tomatoes" />
		</div>

	</body>
</html>

<?php
	# Create a banner with rate and corresponding image with class name "rateBanner".
	# Accpets the rate of the movie.
	# Use freshlarge.png if rate is 60% or above,
	# Otherwise, use rottenlarge.png.
	function rate_banner($rate) {
		if ($rate < 60) {
			$rate_img = "rottenlarge.png";
			$rate_alt = "Rotten";
		} else {
			$rate_img = "freshlarge.png";
			$rate_alt = "Fresh";
		}
	?>

		<div class="rateBanner">
			<img src="https://webster.cs.washington.edu/images/<?= $rate_img ?>" alt="<?= $rate_alt ?>" />
			<span><?= $rate ?>%</span>
		</div>

	<?php
	}

	# Create the overview list of given movie.
	function overview_list($movie) {
		$overview_lines = file("$movie/overview.txt", FILE_IGNORE_NEW_LINES);
	?>

		<dl>

	<?php
		foreach ($overview_lines as $overview_line) {
			list($name, $content) = explode(":", $overview_line);
	?>

			<dt><?= $name ?></dt>
			<dd><?= $content ?></dd>

	<?php
		}
	?>

		</dl>

	<?php
	}

	# Create two columns of reviews of given movie.
	# If a movie has odd number of reviews, the left column has extra one.
	function review_columns($reviews) {
		$num_of_reviews = count($reviews);

		# Calculate the number of reviews in the left column.
		if ($num_of_reviews % 2 == 0) {
			$num_in_left = $num_of_reviews / 2;
		} else {
			$num_in_left = (int)($num_of_reviews / 2) + 1;
		}
	?>
		<div class="review-column">
	<?php
		for ($i = 0; $i < $num_in_left; $i++) { 
			review_ind($reviews[$i]);
		}
	?>
		</div>
		<div class="review-column">
	<?php
		for ($i = $num_in_left; $i < $num_of_reviews; $i++) { 
			review_ind($reviews[$i]);
		}
	?>
		</div>
	<?php
	}

	# Output one review in the given review file.
	function review_ind($review) {
		list($content, $quality, $name, $organization) = file("$review", FILE_IGNORE_NEW_LINES)
	?>
		<p class="review-content">
			<img src="https://webster.cs.washington.edu/images/<?= strtolower($quality) ?>.gif" alt="<?= $quality ?>" />
				<q><?= $content ?></q>
		</p>
		<p class="review-person">
			<img src="https://webster.cs.washington.edu/images/critic.gif" alt="Critic" />
			<?= $name ?><br />
			<span><?= $organization ?></span>
		</p>
	<?php
	}
?>