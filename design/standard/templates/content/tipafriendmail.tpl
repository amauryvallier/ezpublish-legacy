{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{let node=fetch( content, node, hash( node_id, $node_id ) )}
{'This message was sent to you because "%1 <%2>" thought you might find the page "%3" at %4 interesting.'|i18n("design/standard/content/tipafriend",,array($your_name,$your_email,$nodename,$hostname))}

{"This is the link to the page:"|i18n("design/standard/content/tipafriend")}
http://{$hostname}{$node.url|ezurl( no )}

{'Comment by "%1 <%2>":'|i18n("design/standard/content/tipafriend",,array($your_name,$your_email))}
{$comment}

{/let}