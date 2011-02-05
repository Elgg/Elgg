<?php
/**
 * General CSS
 */

$title = 'General CSS';

require dirname(__FILE__) . '/head.php';

?>
<body>
	<div class="elgg-page mal">
		<h1 class="mbl"><a href="index.php">Index</a> > <?php echo $title; ?></h1>
		<h2>Headings</h2>
		<div class="mbl">
			<h1>Level 1 heading</h1>
			<h2>Level 2 heading</h2>
			<h3>Level 3 heading</h3>
			<h4>Level 4 heading</h4>
			<h5>Level 5 heading</h5>
			<h6>Level 6 heading</h6>
		</div>
		<h2>Paragraph</h2>
		<div class="mbl">
		<p>Lorem ipsum dolor sit amet, <a href="#" title="test link">test link</a>
      adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec
      faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero
      nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent
      mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu
      volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus
      eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem,
      consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue
      quis tellus.</p>

      <p>Lorem ipsum dolor sit amet, <em>emphasis</em>
      consectetuer
      adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec
      faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero
      nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent
      mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu
      volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus
      eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem,
      consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue
      quis tellus.</p>
		</div>
		<h2>Misc</h2>
				<p>
					I am <a href="?abc123">the a tag</a> example<br />
					I am <abbr title="test">the abbr tag</abbr> example<br />
					I am <acronym>the acronym tag</acronym> example<br />
					I am <b>the b tag</b> example<br />
					I am <code>the code tag</code> example<br />
					I am <del>the del tag</del> example<br />
					I am <em>the em tag</em> example<br />
					I am <i>the i tag</i> example<br />
					I am <strong>the strong tag</strong> example<br />
				</p>
				<blockquote><p>Paragraph inside Blockquote: <?php echo $ipsum; ?></p></blockquote>
				<pre><strong>Preformated:</strong>Testing one row
				 and another</pre>
	</div>
</body>
</html>