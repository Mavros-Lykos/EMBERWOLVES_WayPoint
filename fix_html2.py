import json

with open('docs/SCREEN_FLOW_RATIONALES.md', encoding='utf-8') as f:
    md_content = f.read()

js_escaped_md = json.dumps(md_content)

html = f'''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screen Flow Diagrams & Rationales</title>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        body {{ font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 1000px; margin: 0 auto; padding: 2rem; background: #fff; }}
        h1, h2, h3 {{ color: #111; margin-top: 2rem; border-bottom: 1px solid #eaecef; padding-bottom: 0.3em; }}
        .mermaid {{ display: flex; justify-content: center; margin: 2rem 0; padding: 1rem; border-radius: 8px; background: #fff; }}
        pre {{ background: #f6f8fa; padding: 16px; border-radius: 6px; overflow: auto; }}
        code {{ font-family: ui-monospace, SFMono-Regular, Consolas, "Liberation Mono", Menlo, monospace; }}
        ul {{ padding-left: 2em; }}
        li {{ margin: 0.25em 0; }}
        strong {{ font-weight: 600; }}
    </style>
</head>
<body>
    <div id="content"></div>
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        
        const markdown = {{js_escaped_md}};
        const html = marked.parse(markdown);
        
        const contentDiv = document.getElementById('content');
        contentDiv.innerHTML = html;
        
        // Find all pre > code.language-mermaid
        const codeBlocks = contentDiv.querySelectorAll('pre code.language-mermaid');
        codeBlocks.forEach(block => {{
            const pre = block.parentElement;
            const div = document.createElement('div');
            div.className = 'mermaid';
            div.textContent = block.textContent;
            pre.parentNode.replaceChild(div, pre);
        }});
        
        mermaid.initialize({{ startOnLoad: false, theme: 'default' }});
        await mermaid.run({{ querySelector: '.mermaid' }});
    </script>
</body>
</html>'''

with open('docs/SCREEN_FLOW_RATIONALES.html', 'w', encoding='utf-8') as f:
    f.write(html.replace('{{js_escaped_md}}', js_escaped_md))
