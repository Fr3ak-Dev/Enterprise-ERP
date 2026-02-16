# Reglas del proyecto

- No incluir "Co-Authored-By" en los mensajes de commit
- No mencionar CLAUDE.md en ningún mensaje de commit
- Usar conventional commits con scope: `type(scope): message`
  - No usar backend, frontend o mobile como scope (las ramas ya los separan)
- Los commits y push se hacen a la rama correspondiente según la carpeta de trabajo:
  - Backend/ → rama `backend`
  - Frontend/ → rama `frontend`
  - Mobile/ → rama `mobile`
- Siempre hacer push a origin después de cada commit
- No hacer merge ni push a `master` a menos que el usuario lo indique explícitamente
- Al hacer merge a `master` desde cualquier rama (backend, frontend, mobile), usar squash merge con un commit descriptivo
