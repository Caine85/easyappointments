<script>
    window.vars = (function () {
        const vars = <?= json_encode(script_vars()) ?>;

        return (key, value) => {
            if (!key) {
                return vars;
            }

            if (value !== undefined) {
                vars[key] = value;
            }

            return vars[key] || undefined;
        };
    })();
</script>

