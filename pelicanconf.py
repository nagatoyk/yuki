AUTHOR = 'Suzumiya Nagato'
SITENAME = "Suzumiya Nagato's Blog"
SITEURL = ""

PATH = "content"

TIMEZONE = 'Asia/Shanghai'

DEFAULT_LANG = 'zh-CN'

# Feed generation is usually not desired when developing
FEED_ALL_ATOM = None
CATEGORY_FEED_ATOM = None
TRANSLATION_FEED_ATOM = None
AUTHOR_FEED_ATOM = None
AUTHOR_FEED_RSS = None

# Blogroll
LINKS = (
    ("Pelican", "https://getpelican.com/"),
    ("Python.org", "https://www.python.org/"),
    ("Jinja2", "https://palletsprojects.com/p/jinja/"),
    ("You can modify those links in your config file", "#"),
)

# Social widget
SOCIAL = (
    ("github", "https://github.com/nagatoyk"),
)

DEFAULT_PAGINATION = 10

# Uncomment following line if you want document-relative URLs when developing
# RELATIVE_URLS = True

OUTPUT_PATH = 'docs'

THEME = 'themes/pelican-alchemy/alchemy'
SITEIMAGE = 'https://gravatar.loli.net/avatar/6cf979ad5605b762e43104e1b2c27ad3?s=320'
PYGMENTS_STYLE = 'friendly'
# BOOTSTRAP_CSS = 'https://bootswatch.com/5/lux/bootstrap.min.css'
DIRECT_TEMPLATES = ['index', 'tags', 'categories', 'authors', 'archives', 'sitemap']
SITEMAP_SAVE_AS = 'sitemap.xml'
ARTICLE_URL = 'posts/{date:%Y}/{date:%m}/{slug}.html'
ARTICLE_SAVE_AS = ARTICLE_URL
DRAFTS_URL = 'drafts/{date:%Y}/{date:%m}/{slug}.html'
DRAFTS_SAVE_AS = ARTICLE_URL
PAGE_URL = 'pages/{slug}.html'
PAGE_SAVE_AS = PAGE_URL

FEED_ALL_RSS = 'feeds/all.rss.xml'
DEFAULT_DATE_FORMAT = '%Y-%m-%d %H:%M:%S'
GITHUB_URL = 'https://github.com/nagatoyk'