const elgg = window.elgg;

if (!elgg || !elgg._complete) {
  throw new Error('Module cannot be used until elgg.js executes');
}

export default elgg;
