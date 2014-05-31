<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:atom="http://www.w3.org/2005/Atom" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output method="html"/>

	<xsl:template match="/atom:feed">
		<xsl:text disable-output-escaping='yes'>
			&lt;!doctype html>
		</xsl:text>
		<html>
			<body>
				<div class="header">
					<div class="row">
						<div class="small-12 columns">
							<h1><xsl:value-of select="atom:title"/></h1>
							<p><xsl:value-of select="atom:subtitle"/></p>
							<a href="" target="_blank"></a>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="small-12 columns">
						<div class="feed_box">
							<input id="url" type="text" value="{$rss_url}"></input><div class="btn link">Load</div>
						</div>
					</div>
				</div>

				<div class="row">
					<xsl:for-each select="atom:entry">
						<div class="small-12 columns">
							<div class="feed_box">
								<div class="row">
									<div class="small-12 columns">
										<h2 class="post_title">
											<xsl:value-of select="atom:title"/>
										</h2>
										<p class="content">
											<xsl:value-of select="atom:content" disable-output-escaping="yes"/>
										</p>
									</div>
								</div>
								<div class="bottom">
									<div class="row">
										<div class="small-12 columns">
											<a class="link" href="{atom:link/@href}" target="_blank">
												<xsl:text disable-output-escaping='yes'>
													Continue Reading &amp;raquo;
												</xsl:text>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</xsl:for-each>
				</div>
			</body>
		 </html>
	</xsl:template>
</xsl:stylesheet>
