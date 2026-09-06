# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/laravel-dataforseo/commits/master)

### Added

- Initial release.
- `DataForSeo` facade and `DataForSeoClient` fluent client for the DataForSEO API.
- `serp()` resource: `google()`, `locations()`, `languages()`.
- `keywords()` resource: `volume()`, `forSite()`, `forKeywords()`, `trends()`.
- `backlinks()` resource: `summary()`, `list()`, `referringDomains()`, `anchors()`, `index()`.
- `onPage()` resource: `audit()`.
- `labs()` resource: `competitors()`, `rankedKeywords()`, `domainIntersection()`.
- `DataForSeoException` thrown on a non-2xx API response.
- Configurable login, password, and base URL via `config/dataforseo.php`.
